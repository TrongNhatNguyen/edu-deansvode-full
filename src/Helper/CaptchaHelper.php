<?php

namespace App\Helper;

use Symfony\Component\HttpKernel\KernelInterface;

class CaptchaHelper
{
    protected $projectDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->projectDir = $kernel->getProjectDir();
    }


    public function createImageCaptcha()
    {
        $image = imagecreatetruecolor(100, 38);
 
        imageantialias($image, true);

        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));

        $textcolors = imagecolorallocate($image, 0x00, 0x00, 0x00);
        // choose font type:
        $fonts = [
            $this->projectDir.'\public\assets\web\fonts\captcha\SyneMono-Regular.ttf',
            $this->projectDir.'\public\assets\web\fonts\captcha\Grandstander-VariableFont_wght.ttf'
        ];

        return [
            'image' => $image,
            'textcolors' => $textcolors,
            'fonts' => $fonts
        ];
    }


    public function generateString($input, $strength = 10)
    {
        $inputLength = strlen($input);
        $randomString = '';
        for ($i = 0; $i < $strength; $i++) {
            $randomCharacter = $input[mt_rand(0, $inputLength - 1)];
            $randomString .= $randomCharacter;
        }

        return $randomString;
    }
}
