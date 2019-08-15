<?php
namespace Core\Router;

class RouteGenerator
{
    const Regex = <<<REGEX
@
    # 匹配后面占位符
    \{
        ([A-Za-z_-]*)
    \}
@x
REGEX;

    public static function parse(string $uri)
    {
        if (!preg_match_all(self::Regex, $uri, $match, PREG_SET_ORDER |
            PREG_OFFSET_CAPTURE)) {
            return [
                'regex' => $uri
            ];
        }
        $offset = 0;
        $data   = [];
        foreach ($match as $set) {
            if ($set[0][1] > $offset) {
                $data[] = substr($uri, $offset, $set[0][1] - $offset);
            }
            $data[] = [
                $set[1][0],
                '[A-Za-z_\-0-9]?'
            ];
            $offset = $set[0][1] + strlen($set[0][0]);
        }
        if ($offset !== strlen($uri)) {
            $data[] = substr($uri, $offset);
        }
        return self::generator($data);
    }

    private static function generator(array $routes)
    {
        $regex = '';
        $params = [];
        foreach ($routes as $route) {
            if (is_string($route)) {
                $regex .= $route;
                continue;
            }
            $params[] = $route[0];
            $regex .= '(' . $route[1] . ')';
        }
        return compact('regex', 'params');
    }
}
