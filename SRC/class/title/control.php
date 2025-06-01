<?php
require('class/title/logic.php');
require('class/title/model.php');
require('class/title/view.php');

function title_control()
{
  switch ($_REQUEST['act']) {

    // タイトル管理一覧
    case 'fTitle':

    case 'fTitleSearch': //タイトル管理画面に情報渡す為の関数
      subFTitle(); //CLASSNO順に並べる
      break;

    case 'fTitleItemSearch': //項目名管理画面に情報渡す為の関数
      subFTitleItem(); //SEQNO順に並べる
      break;

    case 'fTitleEdit': //タイトル編集画面のロジック
      subFTitleEdit();
      break;

    case 'fTitleEditComplete':
      subFTitleEditComplete();
      break;

    case 'fTitleItemEdit': //項目編集画面のロジック
      subFTitleItemEdit();
      break;

    case 'fTitleDelete':
      if (isset($_REQUEST['DocNo'])) {
        // 削除に必要な情報を取得
        $docNoToDelete = $_REQUEST['DocNo'];
        $classNoToDelete = $_REQUEST['classNo'] ?? ''; // classNo も送信するようにHTMLを修正する必要があるかもしれません
        $seqNoToDelete = $_REQUEST['seqNo'] ?? '';     // seqNo も送信するようにHTMLを修正する必要があるかもしれません

        // logic.php の subFTitleDelete が期待する引数を渡す
        subFTitleDelete($classNoToDelete, $docNoToDelete, $seqNoToDelete);
      } else {
        // DocNo が送信されていなかった場合のエラー処理
        echo "エラー：削除する DocNo が指定されていません。";
        // 必要に応じてエラーログへの記録やリダイレクト処理などを追加
      }
      break;
  }
}
