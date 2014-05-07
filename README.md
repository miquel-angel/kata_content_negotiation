Content Negotiation
================
We need to create a class to manage a content negotiation for our REST API.
What is a content negotiation? (http://en.wikipedia.org/wiki/Content_negotiation)
It is a mechanism that takes the headers that we sent to decide what content return (Language, API version, format ...)
We can demand more than one type, with different weight, and the class will return the first type available with the higher weight.
For example,
We have the following languages: "Es, En, It"
And the user ask for the language: "Ca,es;q=0.3,en;q=0.6"
Our class should return: "En"
Other example:
The same available languages,
And the user ask for: "Ca"
Our class should return null.

The q means the weight and if we don't specify it, it should be the max q (value 1).
So our class receives the contents demanded by the user and the contents available, and returns the content that fits best (or null if none fit).
We'll assume that in our system exists other classes that know how take that information from the headers