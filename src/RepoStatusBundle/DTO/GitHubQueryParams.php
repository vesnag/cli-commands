<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\DTO;

class GitHubQueryParams
{
    private ?string $since = null;
    private ?string $until = null;

    /**
     * @var array<string, string>
     */
    private array $additionalParams = [];

    public function setSince(?string $since): self
    {
        $this->since = $since;
        return $this;
    }

    public function getSince(): ?string
    {
        return $this->since;
    }

    public function setUntil(?string $until): self
    {
        $this->until = $until;
        return $this;
    }

    public function getUntil(): ?string
    {
        return $this->until;
    }

    public function addParam(string $key, string $value): self
    {
        $this->additionalParams[$key] = $value;
        return $this;
    }

    /**
     * @return array<string, string|null>
     */
    public function getParams(): array
    {
        $params = array_filter([
            'since' => $this->since,
            'until' => $this->until,
        ]);

        return array_merge($params, $this->additionalParams);
    }
}
