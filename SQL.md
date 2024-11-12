```
SELECT * FROM flashcards f
INNER JOIN flashcard_content fc
ON f.id = fc.flashcard_id 
INNER JOIN contents c 
ON c.id = fc.content_id
WHERE f.id = 2;

SELECT * FROM flashcard_content
WHERE flashcard_id = 2;


SELECT * FROM contents WHERE id IN (2,3,4,5,6,7)
SELECT * FROM languages l 
```