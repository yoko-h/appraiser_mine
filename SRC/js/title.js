//
//タイトル管理チェック
//
function fnFTitleEditCheck(zero) {
  if (zero == 0) {
    tmp = form.classNo.value;
    if (tmp.length == '0') {
      alert('表示順を入力してください');
      return;
    }
    if (tmp.length > 2 || tmp.match(/[^0-9]+/)) {
      alert('表示順は2文字以内の半角数字で入力してください');
      return;
    }
  }
  if (zero !== 0) {
    tmp = form.seqNo.value;
    // console.log(length);
    if (tmp.length == '0') {
      alert('表示順を入力してください');
      return;
    }
    if (tmp == 0) {
      alert('0以外の数字を入力してください');
      return;
    }
    if (tmp.length > 2 || tmp.match(/[^0-9]+/)) {
      alert('表示順は2文字以内の半角数字で入力してください');
      return;
    }
  }
  tmp = form.name.value;
  if (tmp.length == 0) {
    alert('名前を入力してください');
    return;
  }
  if (tmp.length > 100) {
    alert('名前は100文字以内で入力してください');
    return;
  }

  // 確認のダイアログ
  if (confirm('この内容で登録します。よろしいですか？')) {
    form.act.value = 'fTitleEditComplete';
    form.submit();
  }
}

//
//削除
//
//
//削除
//
function fnFTitleDeleteCheck(type, docNo, extraValue) {
  if (confirm('削除します。よろしいですか？')) {
    form.docNoToDelete.value = docNo;
    if (type === 'title') {
      form.delete_classNo.value = extraValue;
    } else if (type === 'item') {
      form.delete_seqNo.value = extraValue;
    }
    form.act.value = 'fTitleDelete';
    form.submit();
  }
}
