# EDI PHP
A standard library for declaring EDI parsers, because that's not a fun thing to do.

### Support for EDI structs

Firstly, you should declare your own data structures using the attributes provided by this library:

```php
// Declaring a standard EDI header line (for line 1)
class EDIHeader extends Registry
{
    #[TextField(2)]
    public string $code;
    #[TextField(6)]
    public string $version;
    #[DateField(14, format: 'YmdHis')]
    public DateTimeInterface $dateTime;
    #[TextField(6)]
    public string $movementId;
    #[TextField(30)]
    public string $acquirerName;
    #[TextField(4)]
    public string $serviceProviderId;
    #[TextField(9)]
    public string $establishmentCode;
    #[TextField(1)]
    public string $processingType;
    #[TextField(6)]
    public string $nseq;
}

// We can parse it directly using a static builder method
$header = EDIHeader::from('A00003.120231001025840000442ADIQ SOLUCOES PAGAMENTOS S.A  0040002783189N000001');
```

### Declaring dynamic parsers
Using our built in line parser, we can create dynamically parsed (by line) types, with the power of generators, these types are parsed on demand:

```php
// Using a standard line parser, iterates over each line
class EDIParser extends LineParser
{
    // Ran on each line iteration
    protected function parse(LineContext $ctx): ?Registry
    {
        [$contents, $number] = $ctx->unwrap();
        // For this EDI file, we can deduce which type it is based on
        // the first 2 letters provided each line.
        $code = substr($contents, 0, 2);

        try {
            return match ($code) {
                EDIRegistry::TYPE_HEADER_START => EDIHeader::from($contents),
                EDIRegistry::TYPE_TRANSACTION_BATCH_START => EDITransactionBatch::from($contents),
                EDIRegistry::TYPE_SALE_RECEIPT => EDISaleReceipt::from($contents),
                // Our LineContext can provide a more verbose message to inform
                // where in our data the error ocurred.
                default => $ctx->raise("Cannot parse EDI of type '$code'", 0),
            };
        } catch (FieldException $e) {
            $ctx->raise($e->getMessage(), $e->getCursor());
        }
    }
}

// Using our parser directly
// Reading directly from stdin
$buffer = Stream::file('php://stdin', 'rb');
$parser = EDIParser::loadFromStream($buffer);

foreach ($parser as $registry) {
    /** @var Registry $registry */
    // We have direct access to our parsed objects on demand
    dump($registry);
}
```

### Understanding parsing errors should not be that hard
We enable out-of-the-box support for friendly contextual messages on any errors that ocurred, it is recommended to follow this pratice to make your life easier in the future.

```js
PHP Fatal error:  Uncaught Kubinyete\Edi\Parser\Exception\ParseException: Line 1: Failed to parse field '202d1001025840' as a date with format 'YmdHis'
Contents: "A00003.1202d1001025840000442ADIQ SOLUCOES PAGAMENTOS S.A  0040002783189N000001"
           --------^
 in /home/vitorkubinyete/code/edi-php/src/Parser/LineContext.php:38