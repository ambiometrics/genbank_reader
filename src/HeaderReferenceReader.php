<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 15:45
 */

namespace edwrodrig\genbank_reader;


class HeaderReferenceReader
{
    /**
     * @var StreamReader
     */
    private $stream;

    /**
     * @var null|string
     */
    private $reference;

    /**
     * @var null|string
     */
    private $authors;

    /**
     * @var null|string
     */
    private $journal;

    /**
     * @var null|string
     */
    private $pubmed;

    /**
     * @var null|string
     */
    private $title;

    /**
     * HeaderReferenceReader constructor.
     * @param StreamReader $stream
     * @throws exception\InvalidHeaderFieldException
     */
    public function __construct(StreamReader $stream) {
        $this->stream = $stream;
        $this->parse();
    }

    /**
     * @throws exception\InvalidHeaderFieldException
     */
    private function parse() {
        $reader = new HeaderFieldReader($this->stream);
        $this->reference = $reader->getContent();


        while ( $field = HeaderFieldReader::getNextField($this->stream) ) {


            if ($field == 'AUTHORS') {
                $reader = new HeaderFieldReader($this->stream);
                $this->authors = $reader->getContent();
            } else if ($field == 'TITLE') {
                $reader = new HeaderFieldReader($this->stream);
                $this->title = $reader->getContent();
            } else if ( $field == 'JOURNAL' ) {
                $reader = new HeaderFieldReader($this->stream);
                $this->journal = $reader->getContent();
            } else if ( $field == 'PUBMED' ) {
                $reader = new HeaderFieldReader($this->stream);
                $this->pubmed = $reader->getContent();
            } else
                break;
        }
    }

    /**
     * Get reference information
     *
     * Publications by the authors of the sequence that discuss the data reported in the record. References are automatically sorted within the record based on date of publication, showing the oldest references first.
     *
     * Some sequences have not been reported in papers and show a status of "unpublished" or "in press". When an accession number and/or sequence data has appeared in print, sequence authors should send the complete citation of the article to update@ncbi.nlm.nih.gov and the GenBank staff will revise the record.
     *
     * Various classes of publication can be present in the References field, including journal article, book chapter, book, thesis/monograph, proceedings chapter, proceedings from a meeting, and patent.
     *
     * The last citation in the REFERENCE field usually contains information about the submitter of the sequence, rather than a literature citation. It is therefore called the "submitter block" and shows the words "Direct Submission" instead of an article title. Additional information is provided below, under the header Direct Submission. Some older records do not contain a submitter block.
     *
     * Entrez Search Field: The various subfields under References are searchable in the Entrez search fields noted below.
     * @return string
     */
    public function getReference() : string {
        return $this->reference;
    }

    /**
     * Get the authors
     *
     * List of authors in the order in which they appear in the cited article.
     *
     * Entrez Search Field: Author [AUTH]
     * Search Tip: Enter author names in the form: Lastname AB (without periods after the initials). Initials can be omitted. Truncation can also be used to retrieve all names that begin with a character string, e.g., Richards* or Boguski M*.
     * @return string
     *
     */
    public function getAuthors() : ?string {
        return $this->authors;
    }

    /**
     * Get the title
     *
     * Title of the published work or tentative title of an unpublished work.
     *
     * Sometimes the words "Direct Submission" instead of an article title. This is usually true for the last citation in the REFERENCE field because it tends to contain information about the submitter of the sequence, rather than a literature citation. The last citation is therefore called the "submitter block". Additional information is provided below, under the header Direct Submission. Some older records do not contain a submitter block.
     *
     * Entrez Search Field: Text Word [WORD]
     *
     * Note: For sequence records, the Title Word [TITL] field of Entrez searches the Definition Line, not the titles of references listed in the record. Therefore, use the Text Word field to search the titles of references (and other text-containing fields).
     *
     * Search Tip: If a search for a specific term does not retrieve the desired records, try other terms that authors might have used, such synonyms, full spellings, or abbreviations. The 'related records' (or 'neighbors') function of Entrez also allows you to broaden your search by retrieving records with similar sequences, regardless of the descriptive terms used by the submitters.
     * @return string
     */
    public function getTitle() : ?string {
        return $this->title;
    }

    /**
     * Get journal
     *
     * MEDLINE abbreviation of the journal name. (Full spellings can be obtained from the Entrez Journals Database.)
     *
     * Entrez Search Field: Journal Name [JOUR]
     *
     * Search Tip: Journal names can be entered as either the full spelling or the MEDLINE abbreviation. You can search the Journal Name field in the Index mode to see the index for that field, and to select one or more journal names for inclusion in your search.
     * @return string
     */
    public function getJournal() : ?string {
        return $this->journal;
    }

    /**
     * Get pubmed
     *
     * PubMed Identifier (PMID).
     *
     * References that include PubMed IDs contain links from the sequence record to the corresponding PubMed record. Conversely, PubMed records that contain accession number(s) in the SI (secondary source identifier) field contain links back to the sequence record(s).
     *
     * Entrez Search Field: It is not possible to search the Nucleotide or Protein sequence databases by PubMed ID. However, you can search the PubMed (literature) database of Entrez for the PubMed ID, and then link to the associated sequence records.
     * @return string
     */
    public function getPubmed() : ?string {
        return $this->pubmed;
    }
}