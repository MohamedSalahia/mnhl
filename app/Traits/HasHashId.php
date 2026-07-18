<?php

namespace App\Traits;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

trait HasHashId
{
    protected static function bootHasHashId(): void
    {
        static::created(function (Model $model) {
            $model->saveHashId();
        });

    }

    public static function keyFromHashId(string $hashId): ?int
    {
        $tablePrefix = substr($hashId, 0, 1);
        $encodedPart = substr($hashId, 1);

        if (strtolower(substr((new static)->getTable(), 0, 1)) !== $tablePrefix) {
            return null;
        }

        $hashId = (new static)->getHashIdInstance();

        $decoded = $hashId->decode($encodedPart);

        if (empty($decoded)) {
            return null;
        }

        return static::find($decoded[0])?->id;
    }

    public static function findByHashId(string $hashId, $withTrashed = false): ?Model
    {
        $prefixLength = config('hash_id.prefix_length', 1);

        $separator = config('hash_id.separator', '');

        $tablePrefix = substr($hashId, 0, $prefixLength);

        $encodedPart = substr($hashId, $prefixLength + strlen($separator));

        if (strtolower(substr((new static)->getTable(), 0, $prefixLength)) !== strtolower($tablePrefix)) {
            return null;
        }

        $hashids = (new static)->getHashIdInstance();

        $decoded = $hashids->decode($encodedPart);

        if (empty($decoded)) {
            return null;
        }

        if ($withTrashed) {
            return static::withTrashed()->find($decoded[0]);
        }

        return static::find($decoded[0]);
    }

    public static function findByHashIdOrFail(string $hashId, $withHashId = false): ?Model
    {
        $prefixLength = config('hash_id.prefix_length', 1);

        $separator = config('hash_id.separator', '');

        $tablePrefix = substr($hashId, 0, $prefixLength);

        $encodedPart = substr($hashId, $prefixLength + strlen($separator));

        if (strtolower(substr((new static)->getTable(), 0, $prefixLength)) !== strtolower($tablePrefix)) {
            return null;
        }

        $hashids = (new static)->getHashIdInstance();

        $decoded = $hashids->decode($encodedPart);

        if (empty($decoded)) {
            return null;
        }

        if ($withHashId) {
            return static::withHashId()->findOrFail($decoded[0]);
        }

        return static::findOrFail($decoded[0]);
    }

    //scopes
    public function scopeFindByHashId($query, string $hashId, $withTrashed = false)
    {
        $model = static::findByHashId($hashId, $withTrashed);

        return $model
            ? $query->where($this->getKeyName(), $model->getKey())->first()
            : $query->whereNull($this->getKeyName());

    }//end of findByHashId

    public function scopeWhereHashId($query, $hashId, $withTrashed = false)
    {
        if (is_array($hashId)) {

            return $query->where(function ($query) use ($hashId, $withTrashed) {

                foreach ($hashId as $id) {
                    $model = static::findByHashId($id, $withTrashed);

                    if ($model) {
                        $query->orWhere($this->getKeyName(), $model->getKey());
                    }
                }

            });
        }

        // Handle single hash ID
        $model = static::findByHashId($hashId, $withTrashed);

        if ($withTrashed) {

            return $model
                ? $query->where($this->getKeyName(), $model->getKey())->withTrashed()
                : $query->whereNull($this->getKeyName())->withTrashed();

        }

        return $model
            ? $query->where($this->getKeyName(), $model->getKey())
            : $query->whereNull($this->getKeyName());
    }

    public function scopeWhereInHashId($query, array $hashIds)
    {
        $ids = collect($hashIds)
            ->map(function ($hashId) {
                $model = static::findByHashId($hashId);
                return $model ? $model->getKey() : null;
            })
            ->filter()
            ->values()
            ->all();

        return empty($ids)
            ? $query->whereNull($this->getKeyName())
            : $query->whereIn($this->getKeyName(), $ids);
    }

    public function scopeWhereNotInHashId($query, array $hashIds)
    {
        $ids = collect($hashIds)
            ->map(function ($hashId) {
                $model = static::findByHashId($hashId);
                return $model ? $model->getKey() : null;
            })
            ->filter()
            ->values()
            ->all();

        return empty($ids)
            ? $query->whereNull($this->getKeyName())
            : $query->whereNotIn($this->getKeyName(), $ids);
    }

    //fun
    public function saveHashId()
    {
        $this->forceFill([
            'hash_id' => $this->generateHashId(),
        ]);

        $this->saveQuietly();

    }// end of saveHashId

    public function getHashIdAttribute()
    {
        return $this->generateHashId();

    }// end of getHashIdAttribute

    public function generateHashId(): string
    {
        $hashId = $this->getHashIdInstance();

        $encodedId = $hashId->encode($this->getKey());

        $prefixLength = config('hash_id.prefix_length');

        $separator = config('hash_id.separator');

        $tablePrefix = strtolower(substr($this->getTable(), 0, $prefixLength));

        return $tablePrefix . $separator . $encodedId;

    }

    protected function getHashIdInstance(): Hashids
    {
        $salt = config('hash_id.salt');
        $length = $this->hashIdLength ?? config('hash_id.length');
        $alphabet = config('hash_id.alphabet');

        return new Hashids($salt, $length, $alphabet);
    }

}

