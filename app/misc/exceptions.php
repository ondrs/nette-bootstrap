<?php

namespace App;

// === Runtime exceptions ======================================================

abstract class RuntimeException extends \RuntimeException
{
}


class InvalidStateException extends RuntimeException
{
}


class DuplicateEntryException extends RuntimeException
{
}


class IOException extends RuntimeException
{
}


class FileNotFoundException extends IOException
{
}


class ImageNotFoundException extends IOException
{
}


// === Logic exceptions ========================================================

class LogicException extends \LogicException
{
}


class InvalidArgumentException extends LogicException
{
}


class NotImplementedException extends LogicException
{
}


class NotSupportedException extends LogicException
{
}


class DeprecatedException extends NotSupportedException
{
}


// === AdminModule exceptions ========================================================

class FormNotExistsException extends InvalidArgumentException
{
}

class GridNotExistsException extends InvalidArgumentException
{
}
