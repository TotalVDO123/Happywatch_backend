<script src="browser-deeplink.js" type="text/javascript"></script>
<script type="text/javascript">
deeplink.setup({
    iOS: {
        appName: "Happy Watch 99",
        appId: "1489697591",
    },
    android: {
        appId: "com.myapp.android"
    }
});

window.onload = function() {
    deeplink.open("com.happywatch99iOS.com://");
}
</script>
