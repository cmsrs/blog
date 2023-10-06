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

    protected $fillable = ['title','description', 'publication_date', 'user_id'];

    protected $casts = ['user_id' => 'integer'];

    public $sortable = ['publication_date'];

    public function import()
    {
        $externalApi = env('EXTERNAL_API', null);
        if(empty($externalApi)){
            throw  new ImportBlogsException( 'Cant find EXTERNAL_API in env file' );
        }
        $data = $this->fetchDataFromApi( $externalApi );
        $this->saveExternalData( $data );

        return true;
    }

    public function saveExternalData( $json )
    {
        $data = json_decode($json, true);
        if( !empty($data['status']) &&  $data['status'] != 'ok'  ){
            throw  new ImportBlogsException( 'wrong API status' );
        }

        if( empty($data['articles']) ){
            Log::debug('not found articles' );
            return false;
        }

        $count =  empty($data["count"]) ? 0 : $data["count"];
        $userId = Auth::user()->id;

        $i = 0;
        foreach($data['articles'] as $d){

            $blog = [];
            $blog['user_id'] = $userId;
            $blog['title'] = $d['title'];
            $blog['description'] = $d['description'];
            $partsDate = explode('T', $d['publishedAt']);
            if( empty($partsDate[0])){
                Log::debug('something wrong with date'.$d['publishedAt']  );
                continue;
            }
            $blog['publication_date'] = $partsDate[0];

            $objBlog = Blog::create($blog);
            if (empty($objBlog->id)) {
                throw new ImportBlogsException("I cant create blog");
            }
            
            $i++;  
        }

        Log::debug('data was saved to db count = '.$count.' / '.$i );
        return true;
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

    public function getDataToFront()
    {
        return Blog::where( 'publication_date', '<', Carbon::now() )->orderBy('publication_date', 'desc')->get()->toArray();
    }

}
