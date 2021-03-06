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
use Pusher\Utils;

class Xizhi extends \Pusher\Channel
{
    private string $type = 'send'; // 推送类型：单点.send，频道.channel
    private string $uri_template = '%s/%s.%s';

    protected string $default_url = 'https://xizhi.qqoq.net';
    protected string $method = Pusher::METHOD_POST;

    public const TYPE_SEND = 'send';
    public const TYPE_CHANNEL = 'channel';

    public function __construct(array $config = [])
    {
        parent::configureDefaults($config);
    }

    public function setType(string $type = self::TYPE_SEND): self
    {
        $this->type = ($type === self::TYPE_CHANNEL) ? self::TYPE_CHANNEL : self::TYPE_SEND;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function doCheck(Message $message): self
    {
        $this->params = $message->getParams();
        $this->request_url = sprintf($this->uri_template, $this->config['url'], $this->token, $this->type);

        return $this;
    }

    public function doAfter(): self
    {
        try {
            $resp = Utils::strToArray($this->content);
            $this->status = $resp['code'] === 200;
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            $this->status = false;
        }

        return $this;
    }
}
