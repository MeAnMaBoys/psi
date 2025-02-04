<?php namespace App\Models;
/**
 * @author Nikola Mirkovic 0325/2017
 */
use CodeIgniter\Model;
/**
 * Klasa koja predstavlja tabelu Prijave_Na_Konkurs u bazi podataka
 * @version 1.0
 */
class PrijaveKonkursModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table      = 'Prijave_Na_Konkurs';
    /**
     * @var string $primaryKey ID
     */
    protected $primaryKey = 'ID_K';
    /**
     * @var string $returnType Type
     */
    protected $returnType = 'object';
    /**
     * @var array $allowedFields Fields
     */
    protected $allowedFields = ['ID_K','ID_Dog'];
}