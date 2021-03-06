<?php declare(strict_types=1);

/*
 * This file is part of Pusher.
 *
 * (c) Jetsung Chan <jetsungchan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pusher\Channel;

use Exception;
use Pusher\Message;
use Pusher\Pusher;

class PushPlus extends \Pusher\Channel
{
    private string $uri_template = '%s/send';

    protected string $default_url = 'https://www.pushplus.plus';
    protected string $method = Pusher::METHOD_JSON;

    public function __construct(array $config = [])
    {
        parent::configureDefaults($config);
    }

    public function doCheck(Message $message): self
    {
        $this->params = $message->getParams();
        $this->params['token'] = $this->token;
        $this->request_url = sprintf($this->uri_template, $this->config['url']);

        return $this;
    }

    public function doAfter(): self
    {
        try {
            $resp = json_decode($this->content, true);
            $this->status = $resp['code'] === 200;

            if (!$this->status) {
                $this->error_message = $resp['msg'];
            }
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            $this->status = false;
        }

        return $this;
    }
}
