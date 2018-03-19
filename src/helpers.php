<?php

if ( ! function_exists('format_money') ) {
    function format_money($value) {
        return app('origami.money.intlFormatter')->format($value);
    }
}
