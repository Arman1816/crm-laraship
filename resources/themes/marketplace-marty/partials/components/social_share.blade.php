<div class="social_share active">
    <ul class="social_icons">
        <li>
            <a href="https://www.facebook.com/sharer.php?u={{ $url }}"
               onClick="openInNewWindow(this.href,this.title);"
               data-toggle="tooltip" data-placement="top" title="Facebook">
                <span class="fa fa-facebook"></span>
            </a>
        </li>
        <li>
            <a  href="https://twitter.com/share?url={{ $url }}&text={{ $title }}" data-toggle="tooltip"
                onClick="openInNewWindow(this.href,this.title);"
                data-placement="top" title="Twitter">
                <span class="fa fa-twitter"></span>
            </a>
        </li>
        <li>
            <a href="https://plus.google.com/share?url={{ $url }}"
               data-toggle="tooltip"
               onClick="openInNewWindow(this.href,this.title);"
               data-placement="top" title="Google +">
                <span class="fa fa-google-plus"></span>
            </a>
        </li>
    </ul>
</div>
