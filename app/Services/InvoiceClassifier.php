<?php

namespace App\Services;

use Phpml\Classification\NaiveBayes;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\ModelManager;

class InvoiceClassifier
{
    private $vectorizer;
    private $tfidf;
    private $classifier;
    private $modelPath;

    public function __construct()
    {
        $this->vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $this->tfidf = new TfIdfTransformer();
        $this->classifier = new NaiveBayes();
        $this->modelPath = storage_path('app/invoice_model.nb');
    }

    /**
     * Train model with existing invoices
     */
    public function train(array $samples, array $labels)
    {
        $this->vectorizer->fit($samples);
        $this->vectorizer->transform($samples);
        $this->tfidf->fit($samples);
        $this->tfidf->transform($samples);
        $this->classifier->train($samples, $labels);

        // Save model
        $manager = new ModelManager();
        $manager->saveToFile($this->classifier, $this->modelPath);
    }

    /**
     * Predict supplier or invoice type
     */
    public function predict(string $text): ?string
    {
        if (!file_exists($this->modelPath)) {
            return null;
        }

        $manager = new ModelManager();
        $this->classifier = $manager->restoreFromFile($this->modelPath);

        $sample = [$text];
        $this->vectorizer->fit($sample);
        $this->vectorizer->transform($sample);
        $this->tfidf->fit($sample);
        $this->tfidf->transform($sample);
        dd($this->classifier->predict($sample));

        return $this->classifier->predict($sample)[0];
    }
}
