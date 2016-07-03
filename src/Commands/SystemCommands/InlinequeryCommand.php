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
use Longman\TelegramBot\Entities\InlineQueryResultArticle;
use Longman\TelegramBot\Request;

/**
 * Inline query command
 */
class InlinequeryCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'inlinequery';
    protected $description = 'Reply to inline query';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $inline_query = $update->getInlineQuery();
        $query = $inline_query->getQuery();

        $data = ['inline_query_id' => $inline_query->getId()];
	$articles = [];
	$id = 001;


	if($query == "") {
		        $articles[] = ['id' => "$id", 'title' => "Info message", 'message_text' => "Info about this bot.", 'input_message_content' => [ 'message_text' => 'This cow will say any message you send her.
To use simply type

@cowsaysbot Text

in any chat and select the animal you want :)
Written by Daniil Gentili (@danogentili, https://daniil.it). Check out my other bots: @video_dl_bot, @mklwp_bot, @caption_ai_bot, @cowsaysbot, @cowthinksbot, @figletsbot, @lolcatzbot, @filtersbot, @id3bot, @pwrtelegrambot, @audiokeychainbot!.
[Source code](http://github.com/danog/cowsaysbot)', 'parse_mode' => 'Markdown' ] ];
	} else {
		foreach (glob("/usr/share/cowsay/cows/*") as $filter) {
		        $articles[] = ['id' => "$id", 'title' => basename($filter, ".cow"), 'message_text' => basename($filter, ".cow") . " filter", 'input_message_content' => [ 'message_text' => '```
' . shell_exec("echo " . escapeshellarg($query) . " | /usr/games/cowsay -f " . $filter) 
. '
```', 'parse_mode' => 'Markdown' ] ];
			$id++;
		}
	}
        $array_article = [];
        foreach ($articles as $article) {
            $array_article[] = new InlineQueryResultArticle($article);
        }
        $data['results'] = '[' . implode(',', $array_article) . ']';

        return Request::answerInlineQuery($data);
    }
}
