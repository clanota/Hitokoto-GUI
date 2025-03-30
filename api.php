<?php
ini_set('default_socket_timeout', 30);

// 复用现有的类型映射
$typeMap = [
    'a' => '动画', 'b' => '漫画', 'c' => '游戏', 'd' => '文学',
    'e' => '原创', 'f' => '网络', 'g' => '其他', 'h' => '影视',
    'i' => '诗词', 'j' => '网易云', 'k' => '哲学', 'l' => '抖机灵'
];

header('Content-Type: application/json; charset=utf-8');

try {
    // 验证参数
    if (!isset($_GET['type'])) {
        throw new Exception('你参数呢？');
    }

    $selectedType = $_GET['type'];
    
    if (!array_key_exists($selectedType, $typeMap)) {
        throw new Exception('你这参数不对啊');
    }

    // 获取远程JSON数据
    $jsonUrl = "https://cdn.jsdelivr.net/gh/hitokoto-osc/sentences-bundle@1.0.399/sentences/{$selectedType}.json";
    $jsonData = file_get_contents($jsonUrl);
    
    if ($jsonData === false) {
        throw new Exception('无法获取JSON数据');
    }

    $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
    
    if (!is_array($data) || empty($data)) {
        throw new Exception('无效的JSON结构或空数据');
    }

    // 随机选择一条
    $randomIndex = array_rand($data);
    $result = [
        'code' => 200,
        'data' => [
            'hitokoto' => $data[$randomIndex]['hitokoto'],
           # 'from' => $data[$randomIndex]['from']
        ]
    ];

} catch (Exception $e) {
    http_response_code(400);
    $result = [
        'code' => 400,
        'message' => $e->getMessage(),
        'data' => ['type_mapping' => $typeMap]
    ];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>