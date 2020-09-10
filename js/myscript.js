var Now = new Date(),
  CurrentDay = Now.getDay(),
  OpeningTime = new Date(Now.getFullYear(), Now.getMonth(), Now.getDate(), 8, 30),
  ClosingTime = new Date(Now.getFullYear(), Now.getMonth(), Now.getDate(), 17, 30),
  Open = (Now.getTime() > OpeningTime.getTime() && Now.getTime() < ClosingTime.getTime());

if (CurrentDay !== 6 && CurrentDay !== 0 && Open) {
    $('.openstatus').toggle();
}