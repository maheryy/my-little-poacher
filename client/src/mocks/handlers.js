import { rest } from "msw";

const handlers = [
  rest.get("/api/endpoint", (req, res, ctx) => {
    return res(ctx.json({ data: "test data" }));
  }),
];

export default handlers;
