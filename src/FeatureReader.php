<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 12-06-18
 * Time: 10:53
 */

namespace edwrodrig\genbank;


class FeatureReader
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * FeatureReader constructor.
     * @param $stream
     * @throws exception\InvalidStreamException
     * @throws exception\InvalidHeaderLineFormatException
     */
    public function __construct($stream) {
        if ( !is_resource($stream) ) {
            throw new exception\InvalidStreamException;
        }
        $this->stream = $stream;
        $this->parse();
    }

    public function parse() {

        //qWarning() << "Estado: FEATURES";
/*
        first = line.mid ( 0 , 21 ).trimmed () ;
        if ( first.length() > 0 && rangeStr.length() > 0 ) {
            if ( type == "source" ) {
                QStringList ts = featureStr.split ( "taxon:" ) ;
                        if ( ts.count() > 1 ) data["TAXID"] = ts[1].replace ( "\"" , "" ) ;
                    } else {
                rangeStr = rangeStr.replace ( QRegExp ( "(<|>|\\(|\\)|join)"), "" ) ;
                rangeStr = rangeStr.replace ( QRegExp ( ",+" ) , "," ) ;
                QMap<QString,QString> tempFeature ;
                        if ( rangeStr.mid ( 0 , 10 ) == "complement" ) {
                            rangeStr = rangeStr.mid ( 10 ) ;
                            tempFeature["complement"] = "1" ;
                        } else tempFeature["complement"] = "0" ;
                        QStringList ts = featureStr.split ( "\n" ) ;
                        //qWarning( "range : %s line : %d" , rangeStr.toLatin1().data() , linecount );

                        foreach( QString str, ts ) {
                    int pos = str.indexOf( "=" );
                            tag = str.mid( 0, pos );
                            QString val = str.mid( pos + 1 ).replace( "\"" , "" );
                            if ( tag == "/locus_tag" ) {
                                tempFeature["locus_tag"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/function" ) {
                                tempFeature["function"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/product" ) {
                                tempFeature["product"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/note" ) {
                                tempFeature["note"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/protein_id" ) {
                                tempFeature["protein_id"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/db_xref" ) {
                                tempFeature["db_xref"] = val;
                                tempFeature["metadata"] += tag+": "+val+"\n";
                            } else if( tag == "/translation" )
                                tempFeature["translation"] = val.replace( QRegExp("\\s"), "" );
                        }
                        ts = rangeStr.split ( "," );
                        int excnt = 1;
                        foreach( QString str, ts ) {
                    if( ! str.isEmpty() ) {
                        for( QMap<QString,QString>::iterator i = tempFeature.begin(); i != tempFeature.end(); i++ )
                                    feature[str].insert( i.key(), i.value() );

                                if( tempFeature.contains( "product" ) && ts.count() > 1 )
                                    feature[str]["product"] += QString( "(Exon; %1)").arg( excnt );
                                excnt++;
                            }
                }
                    }
            rangeStr = featureStr = "" ;
        } if ( first == "source" ) {
            type = "source" ;
            featureStr = "" ;
            rangeStr = line.mid ( 21 ).trimmed() ;
            continue ;
        } else if( first == "CDS" ) {
            type = "cds" ;
            featureStr = "" ;
            rangeStr = line.mid ( 21 ).trimmed () ;
            continue ;
        } else if( first == "tRNA" ) {
            type = "trna" ;
            featureStr = "" ;
            rangeStr = line.mid ( 21 ).trimmed () ;
            continue ;
        } else if( first == "rRNA" ) {
            type = "rrna" ;
            featureStr = "" ;
            rangeStr = line.mid ( 21 ).trimmed () ;
            continue ;
        } else if( first == "mRNA" ) {
            type = "mRNA";
            featureStr = "";
            rangeStr = line.mid ( 21 ).trimmed();
            continue;
        } else if( first == "ORIGIN" ) {
            state = ORIGIN;
            continue;
        }

        if( first.length() == 0 ) {
            QString str = line.mid( 21 ).trimmed();
                    if( str.mid( 0, 1 ) == "/" ) featureStr += QString( "\n%1").arg( str );
                    else featureStr += QString( " %1").arg( str );
                }

    }
*/
}