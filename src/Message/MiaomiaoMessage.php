<?php declare(strict_types=1);

/*
 * This file is part of Pusher.
 *
 * (c) Jetsung Chan <jetsungchan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pusher\Message;

use Pusher\Message;

class MiaomiaoMessage extends Message
{
    private string $send = '';  // 通知内容

    public function __construct(string $send)
    {
        $this->send = $send;
    }

    public function setSend(string $send): self
    {
        $this->send = $send;

        return $this;
    }

    public function getSend(): string
    {
        return $this->send;
    }

    public function generateParams(): self
    {
        $this->params = [
            'send' => $this->send,
        ];

        return $this;
    }
}
