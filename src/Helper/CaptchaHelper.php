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
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}
