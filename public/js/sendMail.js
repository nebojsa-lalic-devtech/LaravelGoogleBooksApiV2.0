function showOrHideMailInput() {
    var sendBookDiv = document.getElementById('send_book_div');
    if (sendBookDiv.style.display === 'none') {
        sendBookDiv.style.display = 'block';
    } else {
        sendBookDiv.style.display = 'none';
    }
}
function showSuccessMessage() {
    setTimeout(function () {
        var successfullySendMail = document.getElementById('successfully_send_mail');
        successfullySendMail.style.display = 'block';
        var sendBookDiv = document.getElementById('send_book_div');
        sendBookDiv.style.display = 'none';
    }, 2000)
}
function showProgressBar() {
    var progressBar = document.getElementById('progress_bar');
    progressBar.style.display = 'block';
    setTimeout(function () {
        progressBar.style.display = 'none';
    }, 1000);
    showSuccessMessage();
}