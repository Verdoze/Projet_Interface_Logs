<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Log;

final class LogsController extends Controller {
    public function index(): void {
        $this->requireLogin();

        $filters = [
            'from'  => $_GET['from']  ?? '',
            'to'    => $_GET['to']    ?? '',
            'text'  => $_GET['text']  ?? '',
            'app'   => $_GET['app']   ?? '',
            'host'  => $_GET['host']  ?? '',
            'level' => $_GET['level'] ?? '',
        ];

        $rows  = Log::list($filters, 200);
        $apps  = Log::distinctApps();
        $hosts = Log::distinctHosts();
        $levels = array_values(Log::$prioToText);

        $this->render('logs/index', compact('filters','rows','apps','hosts','levels'));
    }

    public function export(): void {
        $this->requireLogin();

        $filters = [
            'from'  => $_GET['from']  ?? '',
            'to'    => $_GET['to']    ?? '',
            'text'  => $_GET['text']  ?? '',
            'app'   => $_GET['app']   ?? '',
            'host'  => $_GET['host']  ?? '',
            'level' => $_GET['level'] ?? '',
        ];

        $rows = Log::list($filters, 5000);
        $prioToText = Log::$prioToText;

        // sortie CSV
        if (ob_get_level() > 0) { ob_end_clean(); }
        $filename = 'logs_'.date('Ymd_His').'.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        echo "\xEF\xBB\xBF"; // BOM UTF-8

        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID','Date','Application','Niveau','Hôte','Message']);
        foreach ($rows as $r) {
            $lvl = $prioToText[$r['Priority']] ?? (string)$r['Priority'];
            fputcsv($out, [$r['ID'],$r['ReceivedAt'],$r['SysLogTag'],$lvl,$r['FromHost'],$r['Message']]);
        }
        fclose($out);
        exit;
    }
}
