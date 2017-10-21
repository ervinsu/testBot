<?php

require __DIR__ . '/vendor/autoload.php';

 use \LINE\LINEBot;
 use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
 use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder; 
 use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder; 
 use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;

 use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;	
 use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
 use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
 use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
 use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
 use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
 use \LINE\LINEBot\SignatureValidator as SignatureValidator;
 use LINE\LINEBot\KitchenSink\EventHandler;
 use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Util\UrlBuilder;


 use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder; 
 use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder; 

 use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
 use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
 use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;

 use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
 use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;


// set false for production
$pass_signature = true;

// set LINE channel_access_token and channel_secret
$channel_access_token = "EdEzoa3U3Y4oFmm/fUZ5YklJ8tYu7dIS1CtHIz1ShinR0Wvgr/TmxbJvx0lq7JssiFqNuHyOEE6BJiHv8LmGmE64V08/olQss5FomhKPng2CjYStXquOC1hGFTbfAl2CrHqqekCnafm1oe00MuQO6wdB04t89/1O/w1cDnyilFU=";
$channel_secret = "15070ecfcd72b198b064565e49f42001";

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new \Slim\App($configs);

// buat route untuk url homepage
$app->get('/', function($req, $res)
{
 	echo "welcome to index.php";
});

// buat route untuk webhook
$app->post('/webhook', function (\Slim\Http\Request $request, $response) use ($bot, $pass_signature)
{
    // get request body and line signature header
    $body        = file_get_contents('php://input');
    $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';

    // log body and signature
    file_put_contents('php://stderr', 'Body: '.$body);

    if($pass_signature === false)
    {
        // is LINE_SIGNATURE exists in request header?
        if(empty($signature)){
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if(! SignatureValidator::validateSignature($body, $channel_secret, $signature)){
            return $response->withStatus(400, 'Invalid signature');
        }
    }

    // kode aplikasi nanti disini
	$data = json_decode($body, true);
	if(is_array($data['events'])){
	    foreach ($data['events'] as $event)
	    {
	        if ($event['type'] == 'message')
	        {
	            if($event['message']['type'] == 'text')
	            {
	                // send same message as reply to user
	                $message = $event['message']['text'];
	                if($message == "carousel"){

						$imgUrl = "https://syhobaa.herokuapp.com/index.php/../src/b.jpg";
	                	$uriTemplateActionBuilder1 = new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('go to line','https://line.me');
	                	$messageTemplateActionBuilder1 = new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('BUY','BUY');
	                	$carouselColTempBuilder1 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder('foo', 'bar', $imgUrl,[
	                			$uriTemplateActionBuilder1,
	                			$messageTemplateActionBuilder1,
	                		]);
	                	$carouselColTempBuilder2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder('doo', 'bir', $imgUrl,[
	                			$uriTemplateActionBuilder1,
	                			$messageTemplateActionBuilder1,
	                		]);
	                	$carouselBuilder = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder([
	                			$carouselColTempBuilder1,
	                			$carouselColTempBuilder2,
	                		]);
	                	// $carouselBuilder = new CarouselTemplateBuilder([
	                	// 	new CarouselColumnTemplateBuilder('foo', 'bar', $imgUrl, [
	                	// 		new UriTemplateActionBuilder('go to line','https://line.me'),
	                	// 		new MessageTemplateActionBuilder('BUY','BUY'),2
	                	// 		]),
	                	// 	new CarouselColumnTemplateBuilder('foo', 'bar', $imgUrl, [
	                	// 		new UriTemplateActionBuilder('go to line','https://line.me'),
	                	// 		new MessageTemplateActionBuilder('BUY','BUY'),
	                	// 		]),
	                	// ]);
	                	$TemplateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder   ('terserah',$carouselBuilder);
	                	//$textMessageBuilder = new TextMessageBuilder($imgUrl);
	                	$bot->replyMessage($event['replyToken'], $TemplateMessage);

	                }else if($message == "bcd"){	                	
	                	$textMessageBuilder = new TextMessageBuilder("lu bcd");
	                	$bot->replyMessage($event['replyToken'], $textMessageBuilder);

	                }else if($message == "test"){
	                	$confirmTemplateBuilder = new ConfirmTemplateBuilder("b",[
	                				new MessageTemplateActionBuilder("yes","Yes!"),
	                				new MessageTemplateActionBuilder("no", "No!"),
	                				]);
	                	
	                	$TemplateMessage =  new TemplateMessageBuilder("a",$confirmTemplateBuilder);
						$bot->replyMessage($event['replyToken'],$TemplateMessage);

	                }else if($message == "buttons"){
	                	$imageUrl = "https://syhobaa.herokuapp.com/index.php/../src/d";
	                	$buttonTemplateBuilder = new LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder(
	                		 'asddd',
	                		 'bzasd asfsa', 
	                		 $imageUrl, 
	                		[
	                			new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('Go to line.me', 'https://line.me'),
	                			new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('say hello','hello hello'),
	                			// new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('go to line','https://line.me'),
	                			// new \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder('asdsadas','hello');

	                		]
	                	);

	                	$TemplateMessage = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('Button alt text', $buttonTemplateBuilder);
	                	$bot->replyMessage($event['replyToken'], $TemplateMessage);
	                
	                }
	                else if($message == "url"){
	                	$imageUrl2 = UrlBuilder::buildUrl($request,['..','src','f.jpg']);
	                	$textMessageBuilder = new TextMessageBuilder($imageUrl2);
	                	$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
	              
	                }else if($message == "img"){
	                	// $textMessageBuilder = new TextMessageBuilder("lu bcd");
	                	// $bot->replyMessage($event['replyToken'], $textMessageBuilder);

	                	$richMessageUrl = "https://syhobaa.herokuapp.com/src/d.png"; 
		                 $imagemapMessageBuilder = new ImagemapMessageBuilder( 
		                     $richMessageUrl, 
		                     'This is alt text', 
		                     new BaseSizeBuilder(1040,1040), 
		                     [ 
		                         new ImagemapUriActionBuilder( 
		                             'https://store.line.me/family/manga/en', 
		                             new AreaBuilder(0, 0, 520, 1040) 
		                         ), 
		                         new ImagemapMessageActionBuilder( 
		                             'URANAI!', 
		                             new AreaBuilder(520, 0, 520, 1040) 
		                         ),
		                     ] 
		                 ); 
		                $bot->replyMessage($event['replyToken'], $imagemapMessageBuilder);


	                }else{

		                $textMessageBuilder = new TextMessageBuilder("STFU");
		                $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
		            	
		            	//$result = $bot->replyText($event['replyToken'], $event['message']['text']);

		                // or we can use replyMessage() instead to send reply message
		                // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
		                // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);

		                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
	            	}
	            }
	        }
	    }
	}
});

//pushmessage ke 1 orng
$app->get('/pushmessage', function($req, $res) use ($bot)
{
    // send push message to user
    $userId = 'U9bfed8cfc4fafa38cbea734d52bd8c3d';
    $textMessageBuilder1 = new TextMessageBuilder('WOI COBA TULIS "carousel" mas');
    $stikerMessageBuilder1 = new StickerMessageBuilder(1,106);

    $multiMessageBuilder = new MultiMessageBuilder();
    $multiMessageBuilder->add($textMessageBuilder1);
    $multiMessageBuilder->add($stikerMessageBuilder1);

    $result = $bot->pushMessage($userId, $multiMessageBuilder);
   
    return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
	echo "push berhasil";
});

//push message ke banyak orng
$app->get('/multicast', function($req, $res) use ($bot)
{
    // list of users
    $userList = [
        'U206d25c2ea6bd87c17655609xxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'];

    // send multicast message to user
    $textMessageBuilder = new TextMessageBuilder('Halo, ini pesan multicast');
    $result = $bot->multicast($userList, $textMessageBuilder);
   
    return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
});


$app->run(); 