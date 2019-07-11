<?php

//Limitations
define("USERNAME_MIN_LENGTH", 5);
define("USERNAME_MAX_LENGTH", 20);
define("EMAIL_MIN_LENGTH", 3);
define("EMAIL_MAX_LENGTH", 254);
define("PASSWORD_MIN_LENGTH", 8);
define("PASSWORD_MAX_LENGTH", 30);
define("PASSWORD_SPECIAL_CHARACTERS", "!@$%*&#");
define("PICURL_MIN_LENGTH", 4);
define("PICURL_MAX_LENGTH", 255);
define("PICURL_MAX_BYTE_SIZE", 3145728);
define("PICURL_MAX_HEIGHT", 4096);
define("PICURL_MAX_WIDTH", 3072);
define("BLOGCONTENT_MIN_LENGTH", 4);
define("BLOGCONTENT_MAX_LENGTH", 4000);
define("TAG_MIN_LENGTH", 3);
define("TAG_MAX_LENGTH", 4000);
define("TITLE_MIN_LENGTH", 3);
define("TITLE_MAX_LENGTH", 20);
define("COMMENT_MIN_LENGTH", 3);
define("COMMENT_MAX_LENGTH", 255);
define("PICURL_ALLOWED_EXTENSIONS", array("png"));

define("BANNED_WORDS", array('damnit', 'crap'));

//Regex
define("USERNAME_REGEX", "/^[\w\@_+.!$%()-]{5,20}$/");
define("EMAIL_FULL_REGEX", "#^([a-zA-Z][a-zA-Z\d,_+-]*\@?[a-zA-Z\d,_+-]*[a-zA-Z0-9])\@([a-zA-Z]{1,})\.([a-zA-Z]{1,20})\.?([a-zA-Z]{1,20})?$#");
define("PASSWORD_REGEX", "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@$%^*&#]{8,}$/");
define("POTENTIAL_SQL_INJECTION_CHARS", "/^[^\^\"'£()<>\/\\;:\[\].,`¬\`|]*/");
define("BLOGCONTENT_CUSTOM_URL", '/(\[[U|u][R|r][L|l]=((http[s]?\:\/\/)?((www[\.-])?[a-zA-Z0-9-]+\.[a-zA-Z-]{2,3}(\.[a-zA-Z-]{2,3})*(\/[.a-zA-Z-]*)*)\]\[([a-zA-Z0-9- ]+)\]))+/');
define("BANNED_WORDS_REGEX", '/\b('.  implode("|", BANNED_WORDS).')\b/i');

//Parameters
define("BLOG_VOTE_TYPES", array("LIKE", "DISLIKE"));
define("COMMENT_VOTE_TYPES", array("LIKE", "DISLIKE"));

?>