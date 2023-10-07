<?php

namespace Tests\Feature\Models;

use App\Models\Blog;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;


class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testSaveExternalDataOnce()
    {
        $admin = $this->createAdmin();
        $this->assertNotEmpty($admin->id);
        $this->actingAs($admin);

        $json = '{"status":"ok","count":9,"articles":[{"id":2,"title":"A group of Tesla owners claim to go on hunger strike for Elon Musk\u2019s attention, but there\u2019s more","description":"A group of Tesla owners in Norway claims they are going on a hunger strike to get Elon Musk\u2019s attention about a long series of problems they claim to have with their vehicles.\nHowever, it looks like there might be another motive.\n more\u2026\nThe post A group of Te\u2026","publishedAt":"2022-08-31T10:37:42Z"},{"id":1,"title":"Heavy-duty Trucks Market in Europe Driven by Increasing Demand in Eastern Europe and Growth in Truck Rentals - ResearchAndMarkets.com","description":"DUBLIN--(BUSINESS WIRE)--The \"Heavy-duty Trucks Market in Europe 2021-2025\" report has been added to ResearchAndMarkets.com\'s offering. The heavy-duty trucks market in Europe is poised to grow by 92.85 thousand units during 2021-2025, progressing at a CAGR of\u2026","publishedAt":"2022-08-31T10:51:55Z"},{"id":6,"title":"Toyota triples planned investment to $3.8 billion in U.S. battery plant","description":"By Paul Lienert (Reuters) -   Toyota Motor Corp will boost its planned investment in a new U.S. battery plant from $1.29 billion to $3.8 billion, partly in response to rising consumer demand for electric vehicles, the company said on Wednesday.","publishedAt":"2022-08-31T10:22:17Z"},{"id":3,"title":"Energy industry insiders support Elon Musk\u2019s outlook on the renewable energy transition\u2014even if it\u2019s hard to stomach","description":"There\'s consensus that the \'energy transition\' will involve fossil fuels for a long time to come, \'otherwise civilization will crumble,\' Tesla CEO Elon Musk said in Norway.","publishedAt":"2022-08-31T10:31:39Z"},{"id":16,"title":"Car Companies Are Making a Deadly Mistake With Electric Vehicles","description":"It\u2019s not too late for the U.S. to do something about it.","publishedAt":"2022-08-31T09:45:01Z"},{"id":18,"title":"Chinese electric carmaker BYD plummets after Buffett sale","description":"Shares in Chinese electric carmaker BYD plunged on Wednesday after its largest backer, Warren Buffett\'s Berkshire Hathaway, reduced its stake amid speculation of a potential exit.","publishedAt":"2022-08-31T09:36:46Z"},{"id":4,"title":"California\u2019s Lithium Rush For EV Batteries Hinges On Taming Toxic, Volcanic Brine","description":"The Salton Sea region has one of the largest known reserves of lithium and could power batteries for more than 50 million electric vehicles year. But first, it\'s got to be extracted from hot geothermal brine loaded with toxic material, a process that\'s never \u2026","publishedAt":"2022-08-31T10:30:00Z"},{"id":19,"title":"Toyota earmarks $5.3bn for battery output expansion","description":"Toyota said Wednesday it would ramp up the production of batteries for electric vehicles in Japan and the United States through an investment of up to 730 billion yen ($5.3 billion). Part of the cash is included in a huge two-trillion budget for the developme\u2026","publishedAt":"2022-08-31T09:35:54Z"},{"id":5,"title":"Here\u2019s what might be the trade of 2023. How to dabble in it now.","description":"It\'s on the early side to start playing in China, say strategists at Bank of America, who nonetheless say there are ways to prepare.","publishedAt":"2022-08-31T10:23:00Z"}]}';
        $arrIn = json_decode( $json, true);

        $ret =  (new Blog)->saveExternalData( $json );
        $this->assertTrue($ret['status']);
        $this->assertEquals( $arrIn['count'], $ret['count']);
        $this->assertNotEmpty($ret['msg']);
        $this->assertTrue(str_contains($ret['msg'], 'successful'));
        

        $dbBlogs = Blog::All()->toArray();
        $this->assertEquals($arrIn['count'], count($dbBlogs));
        $this->assertEquals($arrIn['articles'][0]['title'], $dbBlogs[0]['title'] );
        $this->assertEquals($arrIn['articles'][0]['description'], $dbBlogs[0]['description'] );
        $this->assertEquals( explode('T', $arrIn['articles'][0]['publishedAt'])[0], $dbBlogs[0]['publication_date'] );
    }

    public function testSaveExternalDataDuplicateExternalId()
    {
        $admin = $this->createAdmin();
        $this->assertNotEmpty($admin->id);
        $this->actingAs($admin);

        $json = '{"status":"ok","count":9,"articles":[{"id":2,"title":"A group of Tesla owners claim to go on hunger strike for Elon Musk\u2019s attention, but there\u2019s more","description":"A group of Tesla owners in Norway claims they are going on a hunger strike to get Elon Musk\u2019s attention about a long series of problems they claim to have with their vehicles.\nHowever, it looks like there might be another motive.\n more\u2026\nThe post A group of Te\u2026","publishedAt":"2022-08-31T10:37:42Z"},{"id":1,"title":"Heavy-duty Trucks Market in Europe Driven by Increasing Demand in Eastern Europe and Growth in Truck Rentals - ResearchAndMarkets.com","description":"DUBLIN--(BUSINESS WIRE)--The \"Heavy-duty Trucks Market in Europe 2021-2025\" report has been added to ResearchAndMarkets.com\'s offering. The heavy-duty trucks market in Europe is poised to grow by 92.85 thousand units during 2021-2025, progressing at a CAGR of\u2026","publishedAt":"2022-08-31T10:51:55Z"},{"id":6,"title":"Toyota triples planned investment to $3.8 billion in U.S. battery plant","description":"By Paul Lienert (Reuters) -   Toyota Motor Corp will boost its planned investment in a new U.S. battery plant from $1.29 billion to $3.8 billion, partly in response to rising consumer demand for electric vehicles, the company said on Wednesday.","publishedAt":"2022-08-31T10:22:17Z"},{"id":3,"title":"Energy industry insiders support Elon Musk\u2019s outlook on the renewable energy transition\u2014even if it\u2019s hard to stomach","description":"There\'s consensus that the \'energy transition\' will involve fossil fuels for a long time to come, \'otherwise civilization will crumble,\' Tesla CEO Elon Musk said in Norway.","publishedAt":"2022-08-31T10:31:39Z"},{"id":16,"title":"Car Companies Are Making a Deadly Mistake With Electric Vehicles","description":"It\u2019s not too late for the U.S. to do something about it.","publishedAt":"2022-08-31T09:45:01Z"},{"id":18,"title":"Chinese electric carmaker BYD plummets after Buffett sale","description":"Shares in Chinese electric carmaker BYD plunged on Wednesday after its largest backer, Warren Buffett\'s Berkshire Hathaway, reduced its stake amid speculation of a potential exit.","publishedAt":"2022-08-31T09:36:46Z"},{"id":4,"title":"California\u2019s Lithium Rush For EV Batteries Hinges On Taming Toxic, Volcanic Brine","description":"The Salton Sea region has one of the largest known reserves of lithium and could power batteries for more than 50 million electric vehicles year. But first, it\'s got to be extracted from hot geothermal brine loaded with toxic material, a process that\'s never \u2026","publishedAt":"2022-08-31T10:30:00Z"},{"id":19,"title":"Toyota earmarks $5.3bn for battery output expansion","description":"Toyota said Wednesday it would ramp up the production of batteries for electric vehicles in Japan and the United States through an investment of up to 730 billion yen ($5.3 billion). Part of the cash is included in a huge two-trillion budget for the developme\u2026","publishedAt":"2022-08-31T09:35:54Z"},{"id":5,"title":"Here\u2019s what might be the trade of 2023. How to dabble in it now.","description":"It\'s on the early side to start playing in China, say strategists at Bank of America, who nonetheless say there are ways to prepare.","publishedAt":"2022-08-31T10:23:00Z"}]}';
        $arrIn = json_decode( $json, true);

        $ret =  (new Blog)->saveExternalData( $json );
        $this->assertTrue($ret['status']);
        $this->assertEquals( $arrIn['count'], $ret['count']);


        $jsonDuplicate = '{"status":"ok","count":1,"articles":[{"id":2,"title":"DuplicateId  A group of Tesla owners claim to go on hunger strike for Elon Musk\u2019s attention, but there\u2019s more","description":"A group of Tesla owners in Norway claims they are going on a hunger strike to get Elon Musk\u2019s attention about a long series of problems they claim to have with their vehicles.\nHowever, it looks like there might be another motive.\n more\u2026\nThe post A group of Te\u2026","publishedAt":"2022-08-31T10:37:42Z"}]}';

        $ret2 = (new Blog)->saveExternalData( $jsonDuplicate );
        $this->assertTrue($ret2['status']);
        $this->assertEquals(0, $ret2['count']);
        
        //we dont want save data do db, it should be $arrIn['count']
        $dbBlogs = Blog::All()->toArray();
        $this->assertEquals($arrIn['count'], count($dbBlogs));
    }

    public function testSaveExternalUniqItem()
    {
        $admin = $this->createAdmin();
        $this->assertNotEmpty($admin->id);
        $this->actingAs($admin);

        $json = '{"status":"ok","count":9,"articles":[{"id":2,"title":"A group of Tesla owners claim to go on hunger strike for Elon Musk\u2019s attention, but there\u2019s more","description":"A group of Tesla owners in Norway claims they are going on a hunger strike to get Elon Musk\u2019s attention about a long series of problems they claim to have with their vehicles.\nHowever, it looks like there might be another motive.\n more\u2026\nThe post A group of Te\u2026","publishedAt":"2022-08-31T10:37:42Z"},{"id":1,"title":"Heavy-duty Trucks Market in Europe Driven by Increasing Demand in Eastern Europe and Growth in Truck Rentals - ResearchAndMarkets.com","description":"DUBLIN--(BUSINESS WIRE)--The \"Heavy-duty Trucks Market in Europe 2021-2025\" report has been added to ResearchAndMarkets.com\'s offering. The heavy-duty trucks market in Europe is poised to grow by 92.85 thousand units during 2021-2025, progressing at a CAGR of\u2026","publishedAt":"2022-08-31T10:51:55Z"},{"id":6,"title":"Toyota triples planned investment to $3.8 billion in U.S. battery plant","description":"By Paul Lienert (Reuters) -   Toyota Motor Corp will boost its planned investment in a new U.S. battery plant from $1.29 billion to $3.8 billion, partly in response to rising consumer demand for electric vehicles, the company said on Wednesday.","publishedAt":"2022-08-31T10:22:17Z"},{"id":3,"title":"Energy industry insiders support Elon Musk\u2019s outlook on the renewable energy transition\u2014even if it\u2019s hard to stomach","description":"There\'s consensus that the \'energy transition\' will involve fossil fuels for a long time to come, \'otherwise civilization will crumble,\' Tesla CEO Elon Musk said in Norway.","publishedAt":"2022-08-31T10:31:39Z"},{"id":16,"title":"Car Companies Are Making a Deadly Mistake With Electric Vehicles","description":"It\u2019s not too late for the U.S. to do something about it.","publishedAt":"2022-08-31T09:45:01Z"},{"id":18,"title":"Chinese electric carmaker BYD plummets after Buffett sale","description":"Shares in Chinese electric carmaker BYD plunged on Wednesday after its largest backer, Warren Buffett\'s Berkshire Hathaway, reduced its stake amid speculation of a potential exit.","publishedAt":"2022-08-31T09:36:46Z"},{"id":4,"title":"California\u2019s Lithium Rush For EV Batteries Hinges On Taming Toxic, Volcanic Brine","description":"The Salton Sea region has one of the largest known reserves of lithium and could power batteries for more than 50 million electric vehicles year. But first, it\'s got to be extracted from hot geothermal brine loaded with toxic material, a process that\'s never \u2026","publishedAt":"2022-08-31T10:30:00Z"},{"id":19,"title":"Toyota earmarks $5.3bn for battery output expansion","description":"Toyota said Wednesday it would ramp up the production of batteries for electric vehicles in Japan and the United States through an investment of up to 730 billion yen ($5.3 billion). Part of the cash is included in a huge two-trillion budget for the developme\u2026","publishedAt":"2022-08-31T09:35:54Z"},{"id":5,"title":"Here\u2019s what might be the trade of 2023. How to dabble in it now.","description":"It\'s on the early side to start playing in China, say strategists at Bank of America, who nonetheless say there are ways to prepare.","publishedAt":"2022-08-31T10:23:00Z"}]}';
        $arrIn = json_decode( $json, true);

        $ret =  (new Blog)->saveExternalData( $json );
        $this->assertTrue($ret['status']);
        $this->assertEquals( $arrIn['count'], $ret['count']);


        $jsonUniq = '{"status":"ok","count":1,"articles":[{"id":201,"title":"DuplicateId  A group of Tesla owners claim to go on hunger strike for Elon Musk\u2019s attention, but there\u2019s more","description":"A group of Tesla owners in Norway claims they are going on a hunger strike to get Elon Musk\u2019s attention about a long series of problems they claim to have with their vehicles.\nHowever, it looks like there might be another motive.\n more\u2026\nThe post A group of Te\u2026","publishedAt":"2022-08-31T10:37:42Z"}]}';

        $ret2 = (new Blog)->saveExternalData( $jsonUniq );
        $this->assertTrue($ret2['status']);
        $this->assertEquals(1, $ret2['count']);
        
        $dbBlogs = Blog::All()->toArray();
        $this->assertEquals($arrIn['count'] + $ret2['count'], count($dbBlogs));
    }

    public function testGetDataToFront()
    {
        $admin = $this->createAdmin();
        $this->assertNotEmpty($admin->id);

        $user = $this->createUser();
        $this->assertNotEmpty($user->id);

        $b['title'] = 'title1';
        $b['description'] = 'description1';
        $b['publication_date'] = Carbon::now()->subDay(10)->format('Y-m-d');
        $b['user_id'] = $admin->id;
        Blog::create( $b );

        $b0['title'] = 'title2';
        $b0['description'] = 'description2';
        $b0['publication_date'] = Carbon::now()->subDay(2)->format('Y-m-d'); //it will be first
        $b0['user_id'] = $user->id;
        Blog::create( $b0 );

        $b['title'] = 'title3';
        $b['description'] = 'description3';
        $b['publication_date'] = Carbon::now()->subDay(3)->format('Y-m-d');
        $b['user_id'] = $user->id;
        Blog::create( $b );

        $b['title'] = 'title4';
        $b['description'] = 'description4';
        $b['publication_date'] = Carbon::now()->addDay(1)->format('Y-m-d'); //! not appear
        $b['user_id'] = $user->id;
        Blog::create( $b );

        $this->assertEquals(4, Blog::All()->count());

        $blogs =  (new Blog)->getDataToFront();
        $this->assertEquals(3, count($blogs));        
        $this->assertEquals($b0['publication_date'], $blogs[0]['publication_date'] );
        $this->assertEquals($b0['title'], $blogs[0]['title'] );
        $this->assertEquals($b0['description'], $blogs[0]['description'] );
        $this->assertEquals($user->name, $blogs[0]['user_name'] );
        $this->assertEquals(4, count($blogs[0]));
    }


    public function createAdmin()
    {
        $user = new User([
            'email'    => 'admin@example.com',
            'name'     => 'Admin',
            'is_admin' => 1
       ]);

        $user->password = 'secret102';
        $user->save();
        return $user;
    }

    public function createUser()
    {
        $user = new User([
            'email'    => 'user1@example.com',
            'name'     => 'User1',
            'is_admin' => 0
       ]);

        $user->password = 'secret102';
        $user->save();
        return $user;
    }
    
}