<?php
// الحصول على بيانات الطلب المرسلة من AJAX
$prompt = isset($_POST['prompt']) ? $_POST['prompt'] : '';

// تحقق من صحة المدخلات
if (!empty($prompt)) {
    // استدعاء ChatGPT API باستخدام cURL
    $api_key = "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";  // ضع هنا مفتاح API الخاص بك
    $url = "https://api.openai.com/v1/completions";

    $data = array(
        "model" => "gpt-3.5-turbo",
        "prompt" => $prompt,
        "max_tokens" => 100,
        "temperature" => 0.5
    );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: Bearer " . $api_key . "\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo json_encode(["response" => "Error in API request"]);
        exit;
    }

    $response = json_decode($result, true);
    $bot_message = $response['choices'][0]['text'];

    // إعادة الرد إلى الواجهة الأمامية
    echo json_encode(["response" => $bot_message]);
}
?>