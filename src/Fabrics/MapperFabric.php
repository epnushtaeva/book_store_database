<?php

namespace App\Fabrics;

use App\Mappers\{BookMapper, AuthorMapper, ReaderMapper, BookToReaderMapper};
use App\Services\GetEntitiesForMappings\{GetAuthorService, GetBookService, GetReaderService};

abstract class MapperFabric 
{
    private static ?BookMapper $bookMapper = null;
    private static ?AuthorMapper $authorMapper = null;
    private static ?ReaderMapper $readerMapper = null;
    private static ?BookToReaderMapper $bookToReaderMapper = null;
    private static ?GetBookService $bookService = null;
    private static ?GetAuthorService $authorService = null;
    private static ?GetReaderService $readerService = null;


    public static function getBookMapper(): BookMapper
    {
        if(!self::$bookMapper){
            self::$bookMapper = new BookMapper(self::getAuthorService());
        }
        
        return self::$bookMapper;
    }
    
    public static function getAuthorMapper(): AuthorMapper
    {
        if(!self::$authorMapper){
            self::$authorMapper = new AuthorMapper(self::getBookMapper(), self::getBookService());
        }
        
        return self::$authorMapper;
    }
    
     
    public static function getReaderMapper(): ReaderMapper
    {
        if(!self::$readerMapper){
            self::$readerMapper = new ReaderMapper(self::getBookMapper(), self::getBookService());
        }
        
        return self::$readerMapper;
    }
    
     public static function getBookToReaderMapper(): BookToReaderMapper
    {
        if(!self::$bookToReaderMapper){
            self::$bookToReaderMapper = new BookToReaderMapper(self::getBookService(), self::getReaderService());
        }
        
        return self::$bookToReaderMapper;
    }
    
    private static function getAuthorService(): GetAuthorService
    {
        if(!self::$authorService){
            self::$authorService = new GetAuthorService(RepositoryFabric::getAuthorRepository());
        }
        
        return self::$authorService;
    }
    
    private static function getBookService(): GetBookService
    {
        if(!self::$bookService){
            self::$bookService =  new GetBookService(
                                    RepositoryFabric::getBookRepository(), 
                                    RepositoryFabric::getBookToReaderRepository()
            );
        }
        
        return self::$bookService;
    }
    
    private static function getReaderService(): GetReaderService
    {
        if(!self::$readerService){
            self::$readerService =  new GetReaderService(RepositoryFabric::getReaderRepository());
        }
        
        return self::$readerService;
    }
}
