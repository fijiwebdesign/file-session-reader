# PHP Sessions Data Reader

Provides an interface for reading the PHP session data.

PHP alone only gives you access to the current users session. This class allows you to read all session files, and thus allow you to read session data across different users.

This may be useful if you want to copy, backups session data, or transfer to a different format/handler etc..

At the moment it only reads file based session handling, with PHP doing the serializing and handling (the default setting). It does not support other session handlers or serializers.

You can extend the base abstract class for different handlers and serializations.
