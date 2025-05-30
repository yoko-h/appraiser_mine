<?php
//
//タイトル管理画面
//
function subFTitleView($param)
{
?>
  <h1>タイトル管理画面</h1>

  <a href="javascript:form.act.value='fTitleEdit';form.submit();"><img src="./images/btn_enter.png"></a>
  <form name="form" id="form" action="index.php" method="post">
    <input type="hidden" name="act" />
    <input type="hidden" name="sClassNo" /> <!-- 項目管理画面で必要 -->
    <input type="hidden" name="sDocNo" /> <!-- 項目管理画面で必要 -->
    <input type="hidden" name="docNo" /> <!-- タイトル編集画面で必要 -->
    <input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
    <input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
    <!-- <input type="hidden" name="seqNo" /> -->

    <div class="list">
      <table border="0" cellpadding="4" cellspacing="1">
        <tr>
          <th class="list_head">表示順</th>
          <th class="list_head">名前</th>
          <th class="list_head">詳細</th>
          <th class="list_head">編集</th>
        </tr>
        <?php
        $sql = fnSqlFTitleList();
        $res = mysqli_query($param["conn"], $sql);
        $i = 0;
        while ($row = mysqli_fetch_array($res)) {
          $docNo   = htmlspecialchars($row[0]);
          $classNo = htmlspecialchars($row[1]);
          $seqNo   = htmlspecialchars($row[2]);
          $name    = htmlspecialchars($row[3]);
          if ($seqNo == 0) {
        ?>
            <tr>
              <td width="35%" align="center"><?php print $classNo; ?></td>
              <td width="45%"><?php print $name; ?></td>
              <td width="10%"><a href="javascript:form.act.value='fTitleItemSearch';form.sClassNo.value=<?php print $classNo; ?>;form.sDocNo.value=<?php print $docNo; ?>;form.submit();">詳細</a></td>
              <td width="10%"><a href="javascript:form.act.value='fTitleEdit';form.docNo.value=<?php print $docNo; ?>;form.submit();">編集</a></td>
            </tr>
        <?php
          }
          $i = ($i + 1) % 2;
        }
        ?>
      </table>
    </div>
  </form>
<?php
}

//
//項目名管理画面
//
function subFTitleItemView($param)
{
?>
  <h1>項目名管理画面</h1>

  <form name="form" id="form" action="index.php" method="post">
    <input type="hidden" name="act" />
    <input type="hidden" name="sClassNo" value="<?php print $param["sClassNo"] ?>" /><!-- タイトルのclassNo -->
    <input type="hidden" name="sDocNo" value="<?php print $param["sDocNo"] ?>" /><!-- タイトルのdocNo -->
    <input type="hidden" name="orderBy" value="<?php print $param["orderBy"] ?>" />
    <input type="hidden" name="orderTo" value="<?php print $param["orderTo"] ?>" />
    <input type="hidden" name="docNo" /> <!-- 項目編集時に必要 -->

    <table border="0" cellpadding="4" cellspacing="1">
      <tr>
        <th>タイトル</th>
        <td class="f16">
          <?php
          $sql = fnSqlFTitleEdit($param["sDocNo"]); //タイトルのdocNo
          $res = mysqli_query($param["conn"], $sql);
          $row = mysqli_fetch_array($res);
          print htmlspecialchars($row[3]);
          ?>
        </td>
      </tr>
    </table>
    <a href="javascript:form.act.value='fTitleItemEdit';form.submit();"><img src="./images/btn_enter.png"></a>
    <div class="list">
      <table border="0" cellpadding="4" cellspacing="1">
        <tr>
          <th class="list_head">表示順</th>
          <th class="list_head">名前</th>
          <th class="list_head">編集</th>
        </tr>
        <?php

        $sql = fnSqlFTitleItemList($param["sClassNo"]); //classNo に紐づく項目表示したいから
        $res = mysqli_query($param["conn"], $sql);
        $i = 0;
        while ($row = mysqli_fetch_array($res)) {
          $docNo   = htmlspecialchars($row[0]);
          $classNo = htmlspecialchars($row[1]);
          $seqNo   = htmlspecialchars($row[2]);
          $name    = htmlspecialchars($row[3]);

          if ($classNo == $param["sClassNo"] && $seqNo > 0) {
        ?>
            <tr>
              <td width="35%" align="center"><?php print $seqNo; ?></td>
              <td width="45%"><?php print $name; ?></td>
              <td width="10%"><a href="javascript:form.act.value='fTitleItemEdit';form.docNo.value=<?php print $docNo; ?>;form.submit();">編集</a></td>
            </tr>
        <?php
          }
          $i = ($i + 1) % 2;
        }

        ?>
      </table>
    </div>
    <a href="javascript:form.act.value='fTitleSearch';form.submit();"><img src="./images/btn_return.png" /></a>
  </form>
<?php
}

//
//タイトル管理編集画面
//
function subFTitleEditView($param)
{
?>
  <h1>タイトル管理<?php print $param["purpose"]; ?></h1>

  <form name="form" id="form" action="index.php" method="post">
    <input type="hidden" name="act" />
    <input type="hidden" name="orderTo" value="<?php print $param["orderTo"]; ?>" />
    <input type="hidden" name="seqNo" value="<?php print $param["seqNo"] ?? ''; ?>" /><!-- タイトル編集時に必要 -->
    <input type="hidden" name="docNo" value="<?php print $param["DocNo"] ?? ''; ?>" />

    <div class="list">
      <table border="0" cellpadding="5" cellspacing="1">
        <tr>
          <th>表示順<span class="red">（必須）</span></th>
          <td><input type="text" name="classNo" value="<?php print $param["classNo"] ?? ''; ?>" />
            <?php
            if ($param["classNoChk"] ?? '') {
              print "<span class=\"red\" algin=\"right\">" . $param["classNoChk"] . "</span>";
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>名前<span class="red">（必須）</span></th>
          <td><input type="text" name="name" value="<?php print $param["name"] ?? ''; ?>" /></td>
        </tr>
      </table>

    </div>
    <a href="javascript:fnFTitleEditCheck(0);"><img src="./images/<?php print $param["btnImage"]; ?>" /></a>
    <a href="javascript:form.act.value='fTitleSearch';form.submit();"><img src="./images/btn_return.png" /></a>
    <?php
    if ($param["DocNo"]) {
    ?>
      <a href="javascript:fnFTitleDeleteCheck(no);"><img src="./images/btn_del.png" /></a>
    <?php
    }

    ?>
  </form>
  <script type="text/javascript" src="./js/title.js"></script>
<?php
}

//
//項目名管理編集画面
//
function subFTitleItemEditView($param)
{
?>
  <h1>項目名管理<?php print $param["purpose"]; ?></h1>

  <form name="form" id="form" action="index.php" method="post">
    <input type="hidden" name="act" />
    <input type="hidden" name="sDocNo" value="<?php print $param["sDocNo"]; ?>" />
    <input type="hidden" name="orderTo" value="<?php print $param["orderTo"]; ?>" />
    <input type="hidden" name="docNo" value="<?php print $param["DocNo"]; ?>" />

    <div class="list">
      <table border="0" cellpadding="5" cellspacing="1">
        <tr>
          <th>タイトル</th>
          <td class="f16">
            <?php
            $sql = fnSqlFTitleEdit($param["sDocNo"]);
            $res = mysqli_query($param["conn"], $sql);
            $row = mysqli_fetch_array($res);
            print htmlspecialchars($row[3]);
            ?>
            <input type="hidden" name="sClassNo" value="<?php print htmlspecialchars($row[1]); ?>" />
            <input type="hidden" name="classNo" value="<?php print htmlspecialchars($row[1]); ?>" />
            <input type="hidden" name="sName" value="<?php print htmlspecialchars($row[3]); ?>" />
          </td>
        </tr>
        <tr>
          <th>表示順<span class="red">（必須）</span></th>
          <td><input type="text" name="seqNo" value="<?php print $param["seqNo"] ?? ''; ?>" />
            <?php
            if ($param["seqNoChk"] ?? '') {
              print "<span class=\"red\" algin=\"right\">" . $param["seqNoChk"] . "</span>";
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>名前<span class="red">（必須）</span></th>
          <td><input type="text" name="name" value="<?php print $param["name"] ?? ''; ?>" /></td>
        </tr>
      </table>
    </div>
    <a href="javascript:fnFTitleEditCheck();"><img src="./images/<?php print $param["btnImage"]; ?>" /></a>
    <a href="javascript:form.act.value='fTitleItemSearch';form.submit();"><img src="./images/btn_return.png" /></a>
    <?php
    if ($param["DocNo"]) {
    ?>
      <a href="javascript:fnFTitleDeleteCheck(<?php print $param["DocNo"]; ?>);"><img src="./images/btn_del.png" /></a>
    <?php
    }
    ?>
  </form>
  <script type="text/javascript" src="./js/title.js"></script>
<?php
}
?>
