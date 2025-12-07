<?php
header('Content-Type: application/json');

// 1) قراءة الداتا
$input = json_decode(file_get_contents("php://input"), true);

// 2) API Key ديالك من Brevo
$apiKey = "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"; // دخّل API Key هنا

// 3) تجهيز البيانات للإرسال
$data = [
  "email" => [
    "email" => $input['email'],
    "name"  => $input['fullName']
  ],
  "attributes" => [
    "FULLNAME" => $input['fullName'],
    "WHATSAPP" => $input['whatsapp'],
    "CATEGORY" => $input['category'],
    "GOAL"     => $input['goal']
  ],
  "updateEnabled" => true,
  "listIds" => [5]  // رقم اللائحة اللي بغيتي في Brevo
];

// 4) Curl → إرسال للـ API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/contacts");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Accept: application/json",
  "Content-Type: application/json",
  "api-key: $apiKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 5) الرد النهائي للـ frontend
if($code == 200 || $code == 201){
  echo json_encode(["success"=>true]);
} else {
  echo json_encode(["success"=>false, "error"=>$response]);
}
?>
