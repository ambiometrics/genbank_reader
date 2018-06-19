<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 10:53
 */

namespace edwrodrig\genbank_reader;


class FeaturesReader
{
    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var string
     */
    private $description;

    /**
     * @var FeatureFieldReader
     */
    private $source;

    /**
     * @var FeatureFieldReader[]
     */
    private $coding_sequences = [];

    /**
     * @var FeatureFieldReader[]
     */
    private $transfer_rnas = [];

    /**
     * @var FeatureFieldReader[]
     */
    private $messenger_rnas = [];

    /**
     * @var FeatureFieldReader[]
     */
    private $ribosomal_rnas = [];

    private $genes = [];

    private $others = [];

    /**
     * FeatureReader constructor.
     * @param StreamReader $stream
     * @throws exception\InvalidFeatureFieldException
     */
    public function __construct(StreamReader $stream)
    {
        $this->stream = $stream;
        $this->parse();
    }

    static private function readContent(string $line): string
    {
        if ($part = substr($line, 21)) {
            return $part;
        } else {
            return '';
        }
    }

    public function getSource() : FeatureFieldReader {
        return $this->source;
    }

    /**
     * Get the coding sequences
     *
     * @return FeatureFieldReader[]
     */
    public function getCodingSequences(): array
    {
        return $this->coding_sequences;
    }

    /**
     * Get the transfer RNA features
     *
     * @see https://en.wikipedia.org/wiki/Transfer_RNA
     * @return FeatureFieldReader[]
     */
    public function getTransferRnas(): array
    {
        return $this->transfer_rnas;
    }

    /**
     * Get the ribosomal RNA features
     *
     * @see https://en.wikipedia.org/wiki/Ribosomal_RNA
     * @return FeatureFieldReader[]
     */
    public function getRibosomalRnas(): array
    {
        return $this->ribosomal_rnas;
    }

    /**
     * Get the messenger RNA features
     *
     * @see https://en.wikipedia.org/wiki/Messenger_RNA
     * @return FeatureFieldReader[]
     */
    public function getMessengerRnas(): array
    {
        return $this->messenger_rnas;
    }

    /**
     * Get the genes
     *
     * @api
     * @return FeatureFieldReader[]
     */
    public function getGenes() : array {
        return $this->genes;
    }

    /**
     * Get the others
     *
     * @api
     * @return FeatureFieldReader[]
     */
    public function getOthers() : array {
        return $this->others;
    }

    /**
     * Get the description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @throws exception\InvalidFeatureFieldException
     */
    private function parse()
    {

        $line = $this->stream->readLine();
        $this->description = trim(self::readContent($line));

        while ($field = HeaderFieldReader::getNextField($this->stream)) {

            if ($field == 'ORIGIN')
                break;

            if ($field == 'source') {
                $this->source = new FeatureFieldReader($this->stream);
            } else if ($field == 'CDS') {
                $this->coding_sequences[] = new FeatureFieldReader($this->stream);
            } else if ($field == 'tRNA') {
                $this->transfer_rnas[] = new FeatureFieldReader($this->stream);
            } else if ($field == 'rRNA') {
                $this->ribosomal_rnas[] = new FeatureFieldReader($this->stream);
            } else if ($field == 'mRNA') {
                $this->messenger_rnas[] = new FeatureFieldReader($this->stream);
            } else if ( $field == 'gene') {
                $this->genes[] = new FeatureFieldReader($this->stream);
            } else {
                $this->others[$field] = new FeatureFieldReader($this->stream);
            }
        }

    }
}