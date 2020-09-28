<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<?php
set_time_limit(0);
// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://members-api.parliament.uk/api/Posts/GovernmentPosts",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,	
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
"Content-Type: application/json"
));

$response = curl_exec($curl);

curl_close($curl);
$coded = json_decode($response);
$posts=(array)[];
$posts[]=array('id'=>0,'post'=>'');
foreach($coded as $post)
	{
	$posts[]=array('id'=>$post->value->postHolders[0]->member->value->id, 'post'=>$post->value->name);
	}


		echo "<table class='table table-bordered'><thead class='thead-dark'><tr><th scope='col'>id</th><th scope='col'>nameDisplayAs</th><th scope='col'>nameAddressAs</th><th scope='col'>latestParty</th><th scope='col'>Post</th><th scope='col'>Constituency</th><th scope='col'>House</th><th scope='col'>Email</th></tr></thead>";


for($skip=0; $skip<1700; $skip+=20)

{
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://members-api.parliament.uk/api/Members/Search?IsCurrentMember=True&skip=".$skip,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,	
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
"Content-Type: application/json"
));

$response = curl_exec($curl);

curl_close($curl);
$coded = json_decode($response);




foreach($coded->items as $member)
{
$memberValue=$member->value;


$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://members-api.parliament.uk/api/Members/".$memberValue->id."/Contact",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,	
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
"Content-Type: application/json"
));

$response = curl_exec($curl);

curl_close($curl);
$contact = json_decode($response);
$email=$contact->value[0]->email;

if(array_search($memberValue->id, array_column($posts, 'id')))
{
	$key=array_search($memberValue->id, array_column($posts, 'id'));
	$post = $posts[$key]['post'];
}

else

{
	$post="";
}


		$party = $memberValue->latestParty->name;
		$constituency = $memberValue->latestHouseMembership->membershipFrom;
		$house = $memberValue->latestHouseMembership->house;
if($house==1)
{
	$houseName="Commons";
}
else
{
	$houseName="Lords";
}
		echo "<td>$memberValue->id</td>";
		echo "<td>$memberValue->nameDisplayAs</td>";
		echo "<td>$memberValue->nameAddressAs</td>";
		echo "<td>$party</td>";
		echo "<td>$post</td>";
		echo "<td>$constituency</td>";
		echo "<td>$houseName</td>";
		echo "<td>$email</td>";
		echo "</tr>";
	}
	}
	echo "</table>";


?>