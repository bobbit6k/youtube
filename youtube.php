<?php

namespace Plugins\youtube;

use Typemill\Plugin;
use Typemill\Events\OnShortcodeFound;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class youtube extends Plugin implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'onShortcodeFound' => 'onShortcodeFound',
            'onShortcodeRegistration' => 'onShortcodeRegistration',
        );
    }

    public function onShortcodeFound($shortcode)
    {
        $shortcodeArray = $shortcode->getData();

        if (is_array($shortcodeArray) && $shortcodeArray['name'] == 'youtube')
        {
            $shortcode->stopPropagation();

            $videoId = isset($shortcodeArray['params']['id']) ? $shortcodeArray['params']['id'] : '';

            if ($videoId)
            {
                // Sanitize and validate videoId
                $videoId = htmlspecialchars($videoId, ENT_QUOTES, 'UTF-8');
                if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId)) {
                    $embedCode = '<div class="video-container"><iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                    $shortcode->setData($embedCode);
                }
            }
        }
    }

    public function onShortcodeRegistration($shortcode)
    {
        $shortcodeArray = $shortcode->getData();

        if (is_array($shortcodeArray) && $shortcodeArray['name'] == 'registershortcode')
        {
            $shortcodeArray['data']['youtube'] = ['id'];
            $shortcode->setData($shortcodeArray);
        }
    }
}
