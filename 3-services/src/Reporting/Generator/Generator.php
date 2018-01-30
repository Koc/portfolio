<?php

namespace App\Reporting\Generator;

use App\Reporting\Generator\Exception\UnsupportedReportSourceException;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Source\ReportSource;

/**
 * Генерирует отчет на основании данных.
 */
interface Generator
{
    public function getSourceProviderClass(): string;

    /**
     * Возвращает классы, которые занимаются форматированием для каждого из поддерживаемых форматов.
     *
     * @return array<\App\Reporting\Renderer.FORMAT_*, string>
     */
    public function getStylerClasses(): array;

    /**
     * Генерирует отчет на основании данных.
     *
     * @throws UnsupportedReportSourceException В случае, если на вход подали неподдерживаеые данные
     */
    public function generate(ReportSource $reportSource): GeneratedReport;
}
