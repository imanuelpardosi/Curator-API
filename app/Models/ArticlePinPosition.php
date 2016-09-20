<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticlePinPosition extends Model
{
    use SoftDeletes;
    protected $table = "article_pin_position";

    public function article()
    {
        return $this->belongsTo('Kurio\Common\Models\Article', 'article_id');
    }

    public function topic()
    {
        return $this->belongsTo('Kurio\Common\Models\Topic', 'topic_id');
    }
}