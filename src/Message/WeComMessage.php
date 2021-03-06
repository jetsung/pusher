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

class WeComMessage extends Message
{
    public const TYPE_TEXT = 'text';
    public const TYPE_MARKDOWN = 'markdown';
    public const TYPE_IMAGE = 'image';
    public const TYPE_NEWS = 'news';

    private string $msgtype = ''; // 消息类型 text,markdown,image,news
    private string $content = '';     // 通知内容

    // text 类型
    private array $mentionedList = [];       // userid 的列表，提醒群中的指定成员(@某个成员)，@all表示提醒所有人
    private array $mentionedMobileList = []; // 手机号列表，提醒手机号对应的群成员(@某个成员)，@all表示提醒所有人

    // image 类型
    private string $imageBase64 = ''; // 图片内容的 base64 编码（不可换行，不带图片识别头）：base64 -w 0 pic.jpg > encode.log
    private string $imageMd5 = '';    // 图片内容（base64编码前）的md5值：md5sum pic.jpg

    // news 类型
    private array $articles = []; // 图文消息，一个图文消息支持1到8条图文

    public function __construct(
        string $msgtype = '',
        string $content = '',
    ) {
        $this->msgtype = $this->filter_message_type($msgtype);
        $this->content = $content;
    }

    public function setMsgType(string $msgtype): self
    {
        $this->msgtype = $this->filter_message_type($msgtype);

        return $this;
    }

    public function getMsgType(): string
    {
        return $this->msgtype;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setMentionedList(array $list): self
    {
        $this->mentionedList = $list;

        return $this;
    }

    public function getMentionedList(): array
    {
        return $this->mentionedList;
    }

    public function setMentionedMobileList(array $list): self
    {
        $this->mentionedMobileList = $list;

        return $this;
    }

    public function getMetMentionedMobileList(): array
    {
        return $this->mentionedMobileList;
    }

    public function setImageBase64(string $base64): self
    {
        $this->imageBase64 = $base64;

        return $this;
    }

    public function getImageBase64(): string
    {
        return $this->imageBase64;
    }

    public function setImageMd5(string $md5): self
    {
        $this->imageMd5 = $md5;

        return $this;
    }

    public function getImageMd5(): string
    {
        return $this->imageMd5;
    }

    public function setArticles(array $articles): self
    {
        $this->articles = $articles;

        return $this;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }

    public function addArticle(string $title, string $url, string $description = '', string $picurl = ''): self
    {
        $this->articles[] = [
            'title' => $title,
            'url' => $url,
            'description' => $description,
            'picurl' => $picurl,
        ];

        return $this;
    }

    public function generateParams(): self
    {
        $this->params = [
            'msgtype' => $this->msgtype,
        ];

        $params = [];

        switch ($this->msgtype) {
            case 'markdown':
                $params = [
                    'markdown' => [
                        'content' => $this->content,
                    ],
                ];
                break;

            case 'image':
                $params = [
                    'image' => [
                        'base64' => $this->imageBase64,
                        'md5' => $this->imageMd5,
                    ],
                ];
                break;

            case 'news':
                $params = [
                    'news' => [
                        'articles' => $this->articles,
                    ],
                ];
                break;

            case 'text':
            default:
                $params = [
                    'text' => [
                        'content' => $this->content,
                        'mentioned_list' => $this->mentionedList,
                        'mentioned_mobile_list' => $this->mentionedMobileList,
                    ],
                ];
        }

        $this->params += $params;

        return $this;
    }

    private function filter_message_type(string $type): string
    {
        $type = strtolower($type);

        return in_array($type, [ self::TYPE_TEXT, self::TYPE_MARKDOWN, self::TYPE_IMAGE, self::TYPE_NEWS ]) ? $type : self::TYPE_TEXT;
    }
}
