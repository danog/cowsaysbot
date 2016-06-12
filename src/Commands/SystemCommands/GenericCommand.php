<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Generic command
 */
class GenericCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Generic';
    protected $description = 'Handles generic commands or is executed by default when a command is not found';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        //You can use $command as param
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        if (in_array($user_id, $this->telegram->getAdminList()) && strtolower(substr($command, 0, 5)) == 'whois') {
            return $this->telegram->executeCommand('whois', $this->update);
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => "```
" . shell_exec("echo 'This cow will say any message you send her. To use simply type

@cowsaysbot Text

in any chat and select the animal you want :)
Written by Daniil Gentili (@danogentili, https://daniil.it). Check out my other bots: @video_dl_bot, @mklwp_bot, @caption_ai_bot, @cowsaysbot, @cowthinksbot, @figletsbot, @lolcatzbot, @filtersbot, @id3bot, @pwrtelegrambot!
Source code @ http://github.com/danog/cowsaysbot' | /usr/games/cowsay -f apt"). "
```",
		'parse_mode' => "Markdown",
        ];

        return Request::sendMessage($data);
    }
}
