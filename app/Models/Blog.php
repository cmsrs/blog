<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Exceptions\ImportBlogsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory;
    use Sortable;    

    protected $fillable = ['title','description', 'publication_date', 'user_id', 'external_id'];

    protected $casts = ['user_id' => 'integer'];

    public $sortable = ['publication_date'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function import()
    {
        $externalApi = env('EXTERNAL_API', null);
        if(empty($externalApi)){
            throw  new ImportBlogsException( 'Cant find EXTERNAL_API in env file' );
        }
        $data = $this->fetchDataFromApi( $externalApi );
        return $this->saveExternalData( $data );
    }

    public function saveExternalData( $json )
    {
        Log::debug( var_export($json, true ));
        $data = json_decode($json, true);
        if( !empty($data['status']) &&  $data['status'] != 'ok'  ){
            //throw  new ImportBlogsException( 'wrong API status' );
            return [ 'msg' =>'Wrong API status', 'status' => false, 'count' => 0];
        }

        if( empty($data['articles']) ){
            return [ 'msg' =>'Not found articles in json', 'status' => false, 'count' => 0];
        }

        $count =  empty($data["count"]) ? 0 : $data["count"];
        $userId = Auth::user()->id;

        $i = 0;
        $externalIds = Blog::whereNotNull('external_id')->pluck('title', 'external_id'); 

        foreach($data['articles'] as $d){
            $externalId = $d['id'];
            if( !empty($externalIds[$externalId]) ){ //I am not execute db query each time, I am check the record in the memory occurrence
                Log::debug('The record with external_id  ='.$externalId. ' exist in db' );
                continue;
            }

            $blog = [];
            $blog['external_id'] = $externalId;
            $blog['user_id'] = $userId;
            $blog['title'] = $d['title'];
            $blog['description'] = $d['description'];
            $partsDate = explode('T', $d['publishedAt']);
            if( empty($partsDate[0])){
                Log::debug('Something wrong with date'.$d['publishedAt']  );
                continue;
            }
            $blog['publication_date'] = $partsDate[0];

            $objBlog = Blog::create($blog);
            if (empty($objBlog->id)) {
                throw new ImportBlogsException("I cant save blog to db");
            }
            
            $i++;  
        }

        $msg = 'Import successful, data was saved to db, imported items = '.$i.' / '.$count;
        Log::debug($msg );        
        return [ 'msg' =>$msg, 'status' => true, 'count' => $i];
    }

    private function fetchDataFromApi( $externalApi )
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $externalApi,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if($err){
            throw  new ImportBlogsException( 'Curl Error, during fetching data: '. var_export($err, true ) );
        }        
        curl_close($curl);

        return $response;
    }

    /**
     * in that select i execute only two select (it is tweak boost performance):
     * "query" => "select * from `blogs` where `publication_date` < ? order by `publication_date` desc"
     * "query" => "select * from `users` where `users`.`id` in (?, ?)"
     */
    public function getDataToFront()
    {
        //\DB::enableQueryLog(); 
        $ret =  Blog::with(['user'])->where( 'publication_date', '<', Carbon::now() )->orderBy('publication_date', 'desc')->get()->toArray();
        //dd(\DB::getQueryLog()); 

        //I dont want show every information on frontend page
        $out = [];
        foreach($ret as $k=>$v){
            $out[$k]['title'] = $v['title'];
            $out[$k]['description'] = $v['description'];
            $out[$k]['publication_date'] = $v['publication_date'];
            $out[$k]['user_name'] = $v['user']['name'];
        }

        return $out;
    }

}
