<?php
function format_delete_data($data, $route)
{
    switch ($route) {
        case 'user.setup.roles.delete':

            $output = '<style>
           table,th,td,tr {
                border-top: 1px solid black;;
                border-collapse: collapse;
            }
        </style>';
            $output .= '<table>';
            $output .= '<tr>';
            $output .= '<th>Fields</th>';
            $output .= '<th>Data</th>';
            $output .= '</tr>';
            foreach ($data as $key => $value) {
                $output .= '<tr>';
                $output .= '<td>' . $key . '</td>';
                $output .= '<td>' . json_encode($value) . '</td>';
                $output .= '</tr>';
            }
            $output .= '</table>';
            break;

        default:
            $output = '';
            break;

    }

    return $output;
}

function format_post_data($data, $route)
{
    switch ($route) {
        case 'user.setup.roles.add':
            $output = '<style>
           table,th,td,tr {
                border-top: 1px solid black;;
                border-collapse: collapse;
            }
        </style>';
            $output .= '<table>';
            $output .= '<tr>';
            $output .= '<th>Fields</th>';
            $output .= '<th>Data</th>';
            $output .= '</tr>';
            foreach ($data as $key => $value) {
                $output .= '<tr>';
                $output .= '<td>' . $key . '</td>';

                $output .= '<td>';
                if (is_array($value))
                    $output .= json_encode($value);
                else
                    $output .= $value;
                $output .= '</td>';
                $output .= '</tr>';
            }
            $output .= '</table>';
            break;

        default:
            $output = 'asads';
            break;

    }

    return $output;
}
