<?php

namespace App\Reporting\Renderer;

use App\Reporting\Generated\Cell;
use App\Reporting\Renderer;
use App\Reporting\ReportResponse;
use App\Reporting\ReportsStorage;
use Twig\Environment;

class HtmlRenderer implements Renderer
{
    private const TEMPLATE = 'report/reports_collection.html.twig';

    private $storage;

    private $twig;

    private $stylerFactory;

    public function __construct(ReportsStorage $storage, Environment $twig, CssStylerFactory $stylerFactory)
    {
        $this->storage = $storage;
        $this->twig = $twig;
        $this->stylerFactory = $stylerFactory;
    }

    public function renderGeneratedReportCollection(array $reports, array $stylerClasses): ReportResponse
    {
        $html = $this->twig->render(
            self::TEMPLATE,
            ['reports' => $reports, 'stylerClasses' => $stylerClasses, 'renderer' => $this]
        );

        $filename = $this->storage->generateTargetPath(self::getFormat());
        file_put_contents($filename, $html);

        return new ReportResponse($filename);
    }

    public function getCss(array $stylerClasses): string
    {
        $css = [];
        $renderedTableClasses = [];
        foreach ($stylerClasses as $stylerClass) {
            $styler = $this->stylerFactory->getStyler($stylerClass);
            $tableCssClass = $styler->getTableCssCLass();
            if (!empty($renderedTableClasses[$tableCssClass])) {
                continue;
            }
            $css[] = $styler->getCss();
            $renderedTableClasses[$tableCssClass] = true;
        }

        return implode("\n", $css);
    }

    public function getTableCssCLass(string $stylerClass): string
    {
        return $this->stylerFactory->getStyler($stylerClass)->getTableCssCLass();
    }

    public function getCssClassForCell(Cell $cell, string $stylerClass): string
    {
        $styler = $this->stylerFactory->getStyler($stylerClass);

        return implode(' ', $styler->getCssClassesForCell($cell));
    }

    public static function getFormat(): string
    {
        return self::FORMAT_HTML;
    }
}
