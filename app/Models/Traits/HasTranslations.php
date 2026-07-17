<?php

namespace App\Models\Traits;

trait HasTranslations
{
    public function getTranslated(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $translations = $this->translations ?? [];

        if ($locale !== 'id' && isset($translations[$locale][$field])) {
            return $translations[$locale][$field];
        }

        return $this->$field;
    }

    public function setTranslation(string $field, string $locale, string $value): void
    {
        $translations = $this->translations ?? [];
        $translations[$locale][$field] = $value;
        $this->translations = $translations;
    }

    public function scopeWhereLocale($query, ?string $locale = null)
    {
        return $query;
    }
}
