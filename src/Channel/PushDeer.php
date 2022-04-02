<?php declare(strict_types=1);

/*
 * This file is part of Pusher.
 *
 * (c) Jetsung Chan <skiy@jetsung.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pusher\Channel;

use Psr\Http\Message\ResponseInterface;
use Pusher\Message;
use Pusher\Utils;

class PushDeer extends \Pusher\Channel
{
    protected string $base_url = 'https://api2.pushdeer.com';
    protected string $uri_template = '%s/message/push';

    public function __construct(array $config = [])
    {
        parent::configureDefaults($config);
        $this->client = new \GuzzleHttp\Client();
    }

    public function getStatus(): bool
    {
        $resp = Utils::strToArray($this->content);
        $count = count($resp['content']['result']);
        $this->status = $count !== 0;
        $this->showResp();
        return $this->status;
    }
    
    public function request(Message $message): ResponseInterface
    {
        $request_uri = sprintf($this->uri_template, $this->config['base_url']);
        $postData = $message->getParams() + ['pushkey' => $this->token];

        return $this->client->request('POST', $request_uri, [ 'form_params' => $postData]);
    }

}
