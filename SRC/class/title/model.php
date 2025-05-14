<?php

//
// タイトル管理リスト
//
function fnSqlFTitleList($param)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1";
  $order = " ORDER BY " . $param["orderBy"] . " " . $param["orderTo"];
  $sql = $select . $from . $where . $order;
  return $sql;
}

//
// タイトル管理情報
//
function fnSqlFTitleEdit($docNo)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1";
  // $where .= " AND DOCNO = $docNo";
  $where .= " AND DOCNO = " . intval($docNo); // 数値として扱う場合


  return $select . $from . $where;
}

//
// タイトル管理情報更新
//
function fnSqlFTitleUpdate($param)
{
  $sql = "UPDATE TBLDOC";
  $sql .= " SET CLASSNO = '" . $param["classNo"] . "'";
  $sql .= ",SEQNO = '" . $param["seqNo"] . "'";
  $sql .= ",NAME = '" . $param["name"] . "'";
  $sql .= ",UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DocNo = " . $param["DocNo"];

  return $sql;
}

//
// 項目管理情報更新
//
function fnSqlFTitleItemUpdate($param)
{
  $sql = "UPDATE TBLDOC";
  $sql .= " SET CLASSNO = '" . $param["classNo"] . "'";
  $sql .= ",UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DocNo = " . $param["DocNo"];

  return $sql;
}

//
// タイトル管理情報登録
//
function fnSqlFTitleInsert($param)
{
  $sql = "INSERT INTO TBLDOC(";
  $sql .= "DocNo,classNo,seqNo,name,INSDT,UPDT,DEL";
  $sql .= ")VALUES(";
  $sql .= "'" . $param["DocNo"] . "','" . $param["classNo"] . "','" . $param["seqNo"] . "','" . $param["name"] . "',"
    . "CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1)";

  return $sql;
}

//
// タイトル管理情報削除
//
function fnSqlFTitleDelete($docNo)
{
  $sql = "UPDATE TBLDOC";
  $sql .= " SET DEL = -1";
  $sql .= ",UPDT = CURRENT_TIMESTAMP";
  $sql .= " WHERE DOCNO = '$docNo'";

  return $sql;
}

//
// タイトル項目抽出
//
function fnSqlFTitleRepetition($classNo)
{
  $select = "SELECT DOCNO,CLASSNO,SEQNO,NAME";
  $from = " FROM TBLDOC";
  $where = " WHERE DEL = 1 AND CLASSNO = '$classNo'";

  return $select . $from . $where;
}
