<%-- 
ADD GOOGLE ANALYTICS CODE SNIPPET HERE

- Replace the analytics token 'UA-XXXXXXXXX-X' with {$getGoogleAnalyticsKey} in both <script> tags
- Move the analytics token into \app\_config\config.yml where the placeholder 'UA-XXXXXXXXX-X' is

Example of setup below:

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={$getGoogleAnalyticsKey})"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{$getGoogleAnalyticsKey}');
    </script>

--%>