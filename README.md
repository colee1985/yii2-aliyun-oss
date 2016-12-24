yii2-oss
======================
应用于YII2基于阿里云OSS-SDK封装的YII2-OSS-SDK  

install
---------------
composer require colee/yii2-oss

usage
---------------
在配置文件中配置components
``` php
'oss'=>[
    'class'=>'colee\oss\AliOss',
    'bucket'=>'99n9',
    'prefix'=>'chisheng/',
    'AccessKeyId' => '你的AccessKeyId',
    'AccessKeySecret' => '你的AccessKeySecret', 
    'endpoint'=>'oss-cn-hangzhou.aliyuncs.com', // 资源访问地址
    'imageHost' => 'http://99n9.img-cn-hangzhou.aliyuncs.com/' //如果你的OSS backet开启的图片服务就可以配置这里
],
```
常用的方法：
> 
``` php
\Yii::$app->oss->upload2oss($tempName, $path=null); // 将文件上传
\Yii::$app->oss->uploadStream2oss($stream,$path=null); // 将字节流上传
\Yii::$app->oss->getItem($path);  // 获取源文件访问URL，$path为资源在OSS中的路径
\Yii::$app->oss->getImageUrl($path, $style); // 获取缩略图URL，$style对应尺寸样式(在OSS图片服务中定义)
```
更多方法请参考 /aliyun-oss-php-sdk-2.2.1/src/OSS/OssClient.php