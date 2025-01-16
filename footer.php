<footer class="text-center">
    <p>&copy; 2025 SMP Muhammadiyah 32 Jakarta Barat. All rights reserved.</p>
</footer>

<!-- JavaScript -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    // Scroll to top function
    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            document.getElementById("top").style.display = "block";
        } else {
            document.getElementById("top").style.display = "none";
        }
    }
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
</body>
</html>
