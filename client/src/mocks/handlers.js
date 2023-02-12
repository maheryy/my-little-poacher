import { rest } from "msw";
import events from "../../mock_data/models/events.json";

const handlers = [
  rest.get("/events", (req, res, ctx) => {
    return res(ctx.json(events));
  }),
  rest.get("/events/:id", (req, res, ctx) => {
    return res(ctx.json(events[0]));
  }),

  rest.post("/tickets", (req, res, ctx) => {
    return res(ctx.json({ success: true }));
  }),
];

export default handlers;
