<?php

return [
    '100%' => ' style="width: 100%"',
    'solid_colon' => '
        <tr>
            <td class="solid block"><p>[B]</p></td>
            <td class="solid bold"><p>:</p></td>
            <td class="solid line"><p>[L]</p></td>
        </tr>',
    'double_colon' => '
        <tr>
            <td class="double block"><p>[B]</p></td>
            <td class="double bold"><p>:</p></td>
            <td class="double line"><p>[L]</p></td>
        </tr>',
    'colspan' => '
        <tr>
            <td class="[C]" colspan="[I]"><p>[P]</p></td>
        </tr>',
    'hidden' => '
    <input type="hidden" name="[N]" value="[V]">',
    'select' => '<select name="[N]" size="1">[O]</select>',
    'option' => '<option value="[V]"[S]>[O]</option>',
    'boolean' => '<p class="bool">' .
    '<input type="radio" name="[P]" value="1"[T]> <span>[Y]</span>' .
    '<input type="radio" name="[P]" value="0"[F]> <span>[N]</span>' .
    '</p>'
];
