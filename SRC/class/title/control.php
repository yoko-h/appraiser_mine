<?php
require('class/title/logic.php');
require('class/title/model.php');
require('class/title/view.php');

function Ftitle_control()
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

    case 'fTitleEdit':
      subFTitleEdit();
      break;

    case 'fTitleEditComplete':
      subFTitleEditComplete();
      break;

    case 'fTitleItemEdit':
      subFTitleItemEdit();
      break;

    case 'fTitleDelete':
      subFTitleDelete();
      break;
  }
}
