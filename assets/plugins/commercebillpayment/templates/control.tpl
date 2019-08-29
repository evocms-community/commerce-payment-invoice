<script>
    var url = 'commerce/getbill?hash=[+hash+]';

    if (HTMLElement.prototype.click) {
        var link = document.createElement('a');
        link.download = 'bill.pdf';
        link.href = url;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
    } else {
        window.location.href = url;
    }

    setTimeout(function() {
        //window.location = '[+success_page+]';
    }, 5000);
</script>
