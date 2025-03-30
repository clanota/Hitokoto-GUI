<?php
ini_set('default_socket_timeout', 30);

// 处理表单提交
$selectedType = $_GET['type'] ?? 'a';
$typeMap = [
    'a' => '动画', 'b' => '漫画', 'c' => '游戏', 'd' => '文学',
    'e' => '原创', 'f' => '网络', 'g' => '其他', 'h' => '影视',
    'i' => '诗词', 'j' => '网易云', 'k' => '哲学', 'l' => '抖机灵'
];

$jsonUrl = "https://cdn.jsdelivr.net/gh/hitokoto-osc/sentences-bundle@1.0.399/sentences/{$selectedType}.json";

try {
    // 获取并解析JSON数据
    $jsonData = file_get_contents($jsonUrl);
    if ($jsonData === false) throw new Exception('数据异常：无法获取JSON数据');

    $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($data)) throw new Exception('数据异常：无效的JSON结构');

    // 输出HTML内容
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="renderer" content="webkit">
        <link rel="stylesheet" href="https://npm.elemecdn.com/mdui@1.0.2/dist/css/mdui.min.css">
        <title>句子库</title>
    </head>
    <body>
        <div class="mdui-container">
        <br>
        <div class="mdui-card mdui-hoverable">
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">
                        切换目录
                    </div>
                    <div class="mdui-card-primary-subtitle">
                        选择你想看的句子
                    </div>
                </div>
                <div class="mdui-container">
        <form method="GET">
            <div class="mdui-row-xs-2">
            <div class="mdui-col">
            <select name="type" class="mdui-select">';

    foreach ($typeMap as $key => $name) {
        $selected = $key === $selectedType ? 'selected' : '';
        echo "<option value='$key' $selected>$name</option>";
    }

    echo '</select>
           </div>
           <div class="mdui-col">
           <button type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-btn-block">查询</button>
            </div>
            </div>
        </form>
        <br>
        </div>
        </div>
        <br>
        <div class="entries">
        <div class="mdui-card mdui-hoverable">';

    foreach ($data as $item) {
        echo "<div class='mdui-card-content'>
           <div class='hitokoto'>{$item['hitokoto']}</div>
           <!--<div class='from'>出自：{$item['from']}</div>-->
           </div>
           </div>
           <br>
           <div class='mdui-card mdui-hoverable'>";
    }

    echo '</div>
    <script src="https://npm.elemecdn.com/mdui@1.0.2/dist/js/mdui.min.js"></script>
    </div>
    </body>
    </html>';

} catch (Exception $e) {
    http_response_code(500);
    echo "远端服务器错误：{$e->getMessage()}";
}
?>