<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ('test' !== $_ENV['APP_ENV']) {
            $domains = str_replace(';', ' ', $_ENV['DOMAINS']);

            $contentSecurityPolicy = [
                'object-src' => "object-src {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'script-src' => "script-src 'self' 'unsafe-inline' blob: data: code.jquery.com www.nutella.com static.addtoany.com www.google.com www.gstatic.com www.google.com ferrero.containers.piwik.pro cdnjs.cloudflare.com www.googletagmanager.com cdn.cookielaw.org p.teads.tv eu-ma.sam4m.com connect.facebook.net www.kinder.com ga.jspm.io {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'form-action' => "form-action 'self' www.facebook.com",
                'frame-ancestors' => "frame-ancestors 'self' www.google.com {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'connect-src' => "connect-src XMLHttpRequest WebSockets EventSource randomuser.me cdn.cookielaw.org data: static.addtoany.com privacyportal-eu.onetrust.com cm.teads.tv t.teads.tv www.google.com {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'font-src' => "font-src fonts cdn.jsdelivr.net fonts.gstatic.com {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'frame-src' => "frame-src frame www.google.com static.addtoany.com 9206183.fls.doubleclick.net 8622235.fls.doubleclick.net 14003260.fls.doubleclick.net 11798662.fls.doubleclick.net fledge.teads.tv www.facebook.com {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'img-src' => "img-src images randomuser.me www.nutella.com sogec-marketing.web.oxv.fr www.static.ferrero.com data: cdn.cookielaw.org www.facebook.com cm.teads.tv www.kinder.com t.teads.tv ad.doubleclick.net www.googletagmanager.com {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'media-src' => "media-src audio/video {$_ENV['RQST_CTXT_HOST']} {$domains}",
                'style-src' => "style-src cdn.jsdelivr.net www.google.com fonts.googleapis.com 'unsafe-inline' cdnjs.cloudflare.com {$_ENV['RQST_CTXT_HOST']} {$domains}"
            ];

            $event->getResponse()->headers->add([
                'X-XSS-Protection' => "1; mode=block",
                'X-Frame-Options' => "DENY",
                'X-Content-Type-Options' => "nosniff",
                'Content-Security-Policy' => implode("; ", $contentSecurityPolicy),
                'Strict-Transport-Security' => "max-age=63072000; includeSubDomains; preload;",
                'Permissions-Policy' => "camera=(self)",
                'Referrer-Policy' => "no-referrer-when-downgrade",
            ]);
        } else {
            $event->getResponse()->headers->add([
                'X-XSS-Protection' => "1; mode=block",
                'X-Frame-Options' => "DENY",
                'X-Content-Type-Options' => "nosniff",
                'Content-Security-Policy' => "object-src 'none'; script-src 'self' 'unsafe-inline' js-agent.newrelic.com code.jquery.com www.nutella.com  static.addtoany.com www.google.com www.gstatic.com ; form-action 'self'; frame-ancestors 'self';",
                'Strict-Transport-Security' => "max-age=63072000; includeSubDomains; preload;",
            ]);
        }
    }
}

